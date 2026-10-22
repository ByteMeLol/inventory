<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryAuditLog;
use App\Models\InventoryMovement;
use App\Models\InventoryReservation;
use App\Models\InventoryStockBalance;
use App\Models\User;
use App\Models\WarehouseLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryStockService
{
    public function receive(
        int $companyId,
        int $inventoryId,
        int $locationId,
        float $quantity,
        ?float $unitCost = null,
        ?string $reason = null,
        ?User $user = null
    ): InventoryMovement {
        $this->assertPositive($quantity);

        return $this->move($companyId, $inventoryId, $locationId, $quantity, 'receipt', $unitCost, $reason, $user);
    }

    public function issue(
        int $companyId,
        int $inventoryId,
        int $locationId,
        float $quantity,
        ?string $reason = null,
        ?User $user = null
    ): InventoryMovement {
        $this->assertPositive($quantity);

        return $this->move($companyId, $inventoryId, $locationId, -$quantity, 'issue', null, $reason, $user);
    }

    public function adjust(
        int $companyId,
        int $inventoryId,
        int $locationId,
        float $quantity,
        string $reason,
        ?User $user = null
    ): InventoryMovement {
        return $this->move($companyId, $inventoryId, $locationId, $quantity, 'adjustment', null, $reason, $user);
    }

    public function transfer(
        int $companyId,
        int $inventoryId,
        int $fromLocationId,
        int $toLocationId,
        float $quantity,
        ?string $reason = null,
        ?User $user = null
    ): array {
        $this->assertPositive($quantity);

        return DB::transaction(function () use (
            $companyId, $inventoryId, $fromLocationId, $toLocationId, $quantity, $reason, $user
        ) {
            $source = $this->move($companyId, $inventoryId, $fromLocationId, -$quantity, 'transfer_out', null, $reason, $user);
            $destination = $this->move($companyId, $inventoryId, $toLocationId, $quantity, 'transfer_in', null, $reason, $user);

            return [$source, $destination];
        });
    }

    public function reserve(
        int $companyId,
        int $inventoryId,
        int $locationId,
        float $quantity,
        ?User $user = null
    ): InventoryReservation {
        $this->assertPositive($quantity);

        return DB::transaction(function () use ($companyId, $inventoryId, $locationId, $quantity, $user) {
            $balance = $this->lockedBalance($companyId, $inventoryId, $locationId);
            if ((float) $balance->on_hand - (float) $balance->reserved < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient available stock to reserve this quantity.']);
            }

            $balance->increment('reserved', $quantity);

            return InventoryReservation::create([
                'company_id' => $companyId,
                'inventory_id' => $inventoryId,
                'warehouse_location_id' => $locationId,
                'quantity' => $quantity,
                'status' => 'active',
                'created_by' => $user?->id,
            ]);
        });
    }

    public function release(InventoryReservation $reservation): void
    {
        DB::transaction(function () use ($reservation) {
            if ($reservation->status !== 'active') {
                return;
            }

            $balance = $this->lockedBalance(
                $reservation->company_id,
                $reservation->inventory_id,
                $reservation->warehouse_location_id
            );
            $balance->decrement('reserved', $reservation->quantity);
            $reservation->update(['status' => 'released']);
        });
    }

    private function move(
        int $companyId,
        int $inventoryId,
        int $locationId,
        float $quantity,
        string $type,
        ?float $unitCost,
        ?string $reason,
        ?User $user
    ): InventoryMovement {
        return DB::transaction(function () use (
            $companyId, $inventoryId, $locationId, $quantity, $type, $unitCost, $reason, $user
        ) {
            $balance = $this->lockedBalance($companyId, $inventoryId, $locationId);
            $availableAfterMove = (float) $balance->on_hand + $quantity - (float) $balance->reserved;
            if ($availableAfterMove < 0) {
                throw ValidationException::withMessages(['quantity' => 'This operation would make stock negative.']);
            }

            $before = [
                'on_hand' => (float) $balance->on_hand,
                'reserved' => (float) $balance->reserved,
            ];
            $balance->increment('on_hand', $quantity);
            $balance->refresh();

            $movement = InventoryMovement::create([
                'company_id' => $companyId,
                'inventory_id' => $inventoryId,
                'warehouse_location_id' => $locationId,
                'user_id' => $user?->id,
                'type' => $type,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'reason' => $reason,
                'occurred_at' => now(),
            ]);
            InventoryAuditLog::create([
                'company_id' => $companyId,
                'inventory_id' => $inventoryId,
                'user_id' => $user?->id,
                'action' => 'stock.'.$type,
                'quantity' => $quantity,
                'before_state' => $before,
                'after_state' => [
                    'on_hand' => (float) $balance->on_hand,
                    'reserved' => (float) $balance->reserved,
                ],
                'metadata' => ['reason' => $reason],
            ]);

            return $movement;
        });
    }

    private function assertPositive(float $quantity): void
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be greater than zero.']);
        }
    }

    private function lockedBalance(int $companyId, int $inventoryId, int $locationId): InventoryStockBalance
    {
        Inventory::query()->where('id', $inventoryId)->where('company_id', $companyId)->firstOrFail();
        WarehouseLocation::query()
            ->whereKey($locationId)
            ->whereHas('warehouse', fn ($query) => $query->where('company_id', $companyId))
            ->firstOrFail();

        InventoryStockBalance::query()->firstOrCreate([
            'company_id' => $companyId,
            'inventory_id' => $inventoryId,
            'warehouse_location_id' => $locationId,
        ]);

        return InventoryStockBalance::query()
            ->where('company_id', $companyId)
            ->where('inventory_id', $inventoryId)
            ->where('warehouse_location_id', $locationId)
            ->lockForUpdate()
            ->firstOrFail();
    }
}
