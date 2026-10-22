<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryAlert;
use App\Models\InventoryApproval;
use App\Models\InventoryReturnLine;
use App\Models\InventoryStockBalance;
use App\Models\InventoryStocktake;
use App\Models\InventoryStocktakeLine;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryOperationsService
{
    public function alerts(int $companyId): Collection
    {
        $alerts = collect();
        Inventory::where('company_id', $companyId)->with('stockBalances')->each(function (Inventory $item) use ($companyId, $alerts): void {
            $available = $item->stockBalances->sum(fn ($balance) => (float) $balance->on_hand - (float) $balance->reserved);
            if ($available <= (float) ($item->reorder_point ?: $item->safety_threshold)) {
                $alerts->push(InventoryAlert::firstOrCreate(
                    ['company_id' => $companyId, 'inventory_id' => $item->id, 'type' => 'reorder', 'status' => 'open'],
                    ['message' => "{$item->sku} is below its reorder point."]
                ));
            }
            if ($item->track_expiry) {
                $expired = $item->batches()->whereNotNull('expires_at')->whereDate('expires_at', '<=', now()->addDays(30))->exists();
                if ($expired) {
                    $alerts->push(InventoryAlert::firstOrCreate(
                        ['company_id' => $companyId, 'inventory_id' => $item->id, 'type' => 'expiry', 'status' => 'open'],
                        ['message' => "{$item->sku} has stock expiring within 30 days."]
                    ));
                }
            }
        });

        return $alerts;
    }

    public function valuation(int $companyId): Collection
    {
        return Inventory::where('company_id', $companyId)->with('stockBalances')->get()->map(function (Inventory $item) {
            // Legacy SKUs store quantity on inventory.stock_count until they are
            // assigned to a warehouse location.
            $quantity = $item->stockBalances->isNotEmpty()
                ? $item->stockBalances->sum('on_hand')
                : (float) $item->stock_count;
            $unitCost = $item->cost_method === 'fifo'
                ? (float) ($item->batches()->where('quantity', '>', 0)->orderBy('created_at')->value('unit_cost') ?? $item->buying_price ?? 0)
                : (float) ($item->batches()->where('quantity', '>', 0)->avg('unit_cost') ?? $item->buying_price ?? 0);

            return [
                'inventory_id' => $item->id,
                'sku' => $item->sku,
                'cost_method' => $item->cost_method,
                'quantity' => (float) $quantity,
                'unit_cost' => $unitCost,
                'value' => round((float) $quantity * $unitCost, 2),
                'margin' => round((float) ($item->selling_price ?? 0) - $unitCost, 2),
            ];
        });
    }

    public function createReturn(int $companyId, array $data, User $user): InventoryReturnLine
    {
        Inventory::where('id', $data['inventory_id'])->where('company_id', $companyId)->firstOrFail();
        $direction = $data['direction'];
        $return = DB::transaction(function () use ($companyId, $data, $user, $direction) {
            $line = InventoryReturnLine::create([
                ...$data,
                'company_id' => $companyId,
                'created_by' => $user->id,
            ]);
            $movement = app(InventoryStockService::class);
            if ($direction === 'customer') {
                $movement->receive($companyId, $data['inventory_id'], $data['warehouse_location_id'], $data['quantity'], $data['unit_cost'], 'Customer return '.$data['reference_number'], $user);
            } else {
                $movement->issue($companyId, $data['inventory_id'], $data['warehouse_location_id'], $data['quantity'], 'Supplier return '.$data['reference_number'], $user);
            }
            return $line;
        });

        return $return;
    }

    public function startStocktake(int $companyId, array $data, User $user): InventoryStocktake
    {
        return DB::transaction(function () use ($companyId, $data, $user) {
            $stocktake = InventoryStocktake::create([
                'company_id' => $companyId,
                'warehouse_location_id' => $data['warehouse_location_id'],
                'reference_number' => $data['reference_number'],
                'created_by' => $user->id,
            ]);
            InventoryStockBalance::where('warehouse_location_id', $data['warehouse_location_id'])
                ->with('inventory')
                ->each(function (InventoryStockBalance $balance) use ($stocktake): void {
                    $stocktake->lines()->create([
                        'inventory_id' => $balance->inventory_id,
                        'expected_quantity' => $balance->on_hand,
                    ]);
                });
            return $stocktake;
        });
    }

    public function reconcileStocktake(InventoryStocktake $stocktake, array $counts, User $user): InventoryStocktake
    {
        return DB::transaction(function () use ($stocktake, $counts, $user) {
            abort_unless($stocktake->status === 'draft', 422, 'This stocktake is already completed.');
            foreach ($counts as $count) {
                $line = $stocktake->lines()->where('inventory_id', $count['inventory_id'])->firstOrFail();
                $line->update([
                    'counted_quantity' => $count['counted_quantity'],
                    'variance' => (float) $count['counted_quantity'] - (float) $line->expected_quantity,
                    'reason' => $count['reason'] ?? null,
                ]);
            }
            $stocktake->update(['status' => 'pending_approval']);
            InventoryApproval::create([
                'company_id' => $stocktake->company_id,
                'approvable_type' => $stocktake::class,
                'approvable_id' => $stocktake->id,
                'action' => 'stocktake.reconcile',
                'requested_by' => $user->id,
            ]);
            return $stocktake->refresh();
        });
    }

    public function approveStocktake(InventoryStocktake $stocktake, User $user): InventoryStocktake
    {
        return DB::transaction(function () use ($stocktake, $user) {
            abort_unless($stocktake->status === 'pending_approval', 422, 'Only pending stocktakes can be approved.');
            foreach ($stocktake->lines as $line) {
                if ((float) $line->variance !== 0.0) {
                    app(InventoryStockService::class)->adjust(
                        $stocktake->company_id,
                        $line->inventory_id,
                        $stocktake->warehouse_location_id,
                        (float) $line->variance,
                        'Approved stocktake '.$stocktake->reference_number,
                        $user
                    );
                }
            }
            $stocktake->update(['status' => 'approved', 'approved_by' => $user->id, 'approved_at' => now()]);
            $stocktake->approvals()->where('status', 'pending')->update([
                'status' => 'approved', 'reviewed_by' => $user->id, 'reviewed_at' => now(),
            ]);
            return $stocktake;
        });
    }
}
