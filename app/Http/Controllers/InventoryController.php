<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryAuditLog;
use App\Models\InventoryStocktake;
use App\Services\InventoryOperationsService;
use App\Models\InventoryReservation;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\InventoryStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGenerator;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;

class InventoryController extends Controller
{
    //
    public function index()
    {
        return view('inventory');
    }

    public function store(Request $request)
    {
        $companyId = session('company_id') ?: Auth::user()?->company_id;
        abort_unless($companyId, 403, 'No active company scope found for inventory creation.');

        $validatedData = $request->validate([
            'sku' => ['required', 'string', 'max:100', Rule::unique('inventory')->where(fn ($q) => $q->where('company_id', $companyId))],
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'category_id' => ['nullable', Rule::exists('inventory_categories', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'unit_id' => ['nullable', Rule::exists('inventory_units', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'barcode' => 'nullable|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'stock_count' => 'required|integer|min:0',
            'safety_threshold' => 'required|integer|min:0',
            'reorder_point' => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'cost_method' => ['nullable', Rule::in(['fifo', 'weighted_average'])],
            'track_batches' => 'sometimes|boolean',
            'track_serials' => 'sometimes|boolean',
            'track_expiry' => 'sometimes|boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);
        $inventory = Inventory::create([
            'company_id' => $companyId,
            'sku' => $validatedData['sku'],
            'description' => $validatedData['description'],
            'category' => $validatedData['category'],
            'category_id' => $validatedData['category_id'] ?? null,
            'unit_id' => $validatedData['unit_id'] ?? null,
            'barcode' => $validatedData['barcode'] ?? null,
            'unit_price' => $validatedData['unit_price'],
            'buying_price' => $validatedData['buying_price'] ?? $validatedData['unit_price'],
            'selling_price' => $validatedData['selling_price'] ?? $validatedData['unit_price'],
            'stock_count' => $validatedData['stock_count'],
            'safety_threshold' => $validatedData['safety_threshold'],
            'reorder_point' => $validatedData['reorder_point'] ?? $validatedData['safety_threshold'],
            'reorder_quantity' => $validatedData['reorder_quantity'] ?? 0,
            'cost_method' => $validatedData['cost_method'] ?? 'weighted_average',
            'track_batches' => $request->boolean('track_batches'),
            'track_serials' => $request->boolean('track_serials'),
            'track_expiry' => $request->boolean('track_expiry'),
            'tax_rate' => $validatedData['tax_rate'] ?? 0,
        ]);
        InventoryAuditLog::create([
            'company_id' => $companyId,
            'inventory_id' => $inventory->id,
            'user_id' => $request->user()?->id,
            'action' => 'product.created',
            'after_state' => $inventory->only(['sku', 'barcode', 'description', 'category_id', 'unit_id']),
        ]);

        return redirect()->route('inventory.view')->with('success', 'Inventory item added successfully!');
    }

    public function lookup(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate(['code' => ['required', 'string', 'max:150']]);
        $item = Inventory::where('company_id', $companyId)
            ->where(fn ($query) => $query->where('barcode', $data['code'])->orWhere('sku', $data['code']))
            ->with('stockBalances.location.warehouse')
            ->firstOrFail();

        return response()->json($item);
    }

    public function barcode(Inventory $inventory)
    {
        abort_unless($inventory->company_id === $this->companyId(), 403);
        $generator = new BarcodeGeneratorSVG();

        return response($generator->getBarcode($inventory->barcode ?: $inventory->sku, BarcodeGenerator::TYPE_CODE_128, 2, 60))
            ->header('Content-Type', 'image/svg+xml');
    }

    public function qrCode(Inventory $inventory)
    {
        abort_unless($inventory->company_id === $this->companyId(), 403);
        $result = (new Builder(
            writer: new SvgWriter(),
            data: route('inventory.lookup', ['code' => $inventory->barcode ?: $inventory->sku]),
            size: 300,
            margin: 10,
        ))->build();

        return response($result->getString())->header('Content-Type', 'image/svg+xml');
    }

    public function alerts(InventoryOperationsService $operations)
    {
        return response()->json($operations->alerts($this->companyId()));
    }

    public function valuation(InventoryOperationsService $operations)
    {
        return response()->json($operations->valuation($this->companyId()));
    }

    public function returnStock(Request $request, InventoryOperationsService $operations)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'direction' => ['required', Rule::in(['customer', 'supplier'])],
            'inventory_id' => ['required', Rule::exists('inventory', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'warehouse_location_id' => ['required', Rule::exists('warehouse_locations', 'id')->where(fn ($q) => $q->whereIn('warehouse_id', \App\Models\Warehouse::where('company_id', $companyId)->select('id')))],
            'reference_number' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_cost' => ['required', 'numeric', 'gte:0'],
            'customer_id' => ['nullable', Rule::exists('customers', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'supplier_id' => ['nullable', Rule::exists('suppliers', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        $return = $operations->createReturn($companyId, $data, $request->user());

        return back()->with('success', ucfirst($data['direction']).' return recorded.');
    }

    public function startStocktake(Request $request, InventoryOperationsService $operations)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'warehouse_location_id' => ['required', Rule::exists('warehouse_locations', 'id')->where(fn ($q) => $q->whereIn('warehouse_id', \App\Models\Warehouse::where('company_id', $companyId)->select('id')))],
            'reference_number' => ['required', 'string', 'max:100'],
        ]);
        $stocktake = $operations->startStocktake($companyId, $data, $request->user());

        return response()->json($stocktake->load('lines'), 201);
    }

    public function reconcileStocktake(Request $request, InventoryStocktake $stocktake, InventoryOperationsService $operations)
    {
        abort_unless($stocktake->company_id === $this->companyId(), 403);
        $data = $request->validate([
            'counts' => ['required', 'array', 'min:1'],
            'counts.*.inventory_id' => ['required', 'integer'],
            'counts.*.counted_quantity' => ['required', 'numeric', 'min:0'],
            'counts.*.reason' => ['nullable', 'string', 'max:255'],
        ]);
        $updated = $operations->reconcileStocktake($stocktake, $data['counts'], $request->user());

        return response()->json($updated->load('lines'));
    }

    public function approveStocktake(Request $request, InventoryStocktake $stocktake, InventoryOperationsService $operations)
    {
        abort_unless($stocktake->company_id === $this->companyId(), 403);
        abort_unless($request->user()->canManageUsers(), 403, 'Only an authorized manager can approve stocktakes.');
        $updated = $operations->approveStocktake($stocktake->load('lines'), $request->user());

        return response()->json($updated);
    }

    public function storeWarehouse(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouses')->where(fn ($q) => $q->where('company_id', $companyId))],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Warehouse::create([...$data, 'company_id' => $companyId]);

        return back()->with('success', 'Warehouse created successfully.');
    }

    public function storeLocation(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'warehouse_id' => ['required', Rule::exists('warehouses', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['storage', 'receiving', 'quarantine', 'dispatch', 'returns'])],
        ]);

        $warehouse = Warehouse::findOrFail($data['warehouse_id']);
        validator($data, [
            'code' => [Rule::unique('warehouse_locations')->where(fn ($q) => $q->where('warehouse_id', $warehouse->id))],
        ])->validate();
        WarehouseLocation::create($data);

        return back()->with('success', 'Warehouse location created successfully.');
    }

    public function receive(Request $request, InventoryStockService $stock)
    {
        return $this->move($request, $stock, 'receive');
    }

    public function issue(Request $request, InventoryStockService $stock)
    {
        return $this->move($request, $stock, 'issue');
    }

    public function adjust(Request $request, InventoryStockService $stock)
    {
        $data = $this->movementData($request, true);
        $stock->adjust($this->companyId(), $data['inventory_id'], $data['warehouse_location_id'], $data['quantity'], $data['reason'], $request->user());

        return back()->with('success', 'Stock adjustment recorded.');
    }

    public function transfer(Request $request, InventoryStockService $stock)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'inventory_id' => ['required', Rule::exists('inventory', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'from_location_id' => ['required', 'different:to_location_id'],
            'to_location_id' => ['required', 'different:from_location_id'],
            'quantity' => ['required', 'numeric', 'min:0.000001'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        $this->assertLocationsBelongToCompany($companyId, [(int) $data['from_location_id'], (int) $data['to_location_id']]);

        $stock->transfer(
            $companyId,
            $data['inventory_id'],
            $data['from_location_id'],
            $data['to_location_id'],
            $data['quantity'],
            $data['reason'] ?? null,
            $request->user()
        );

        return back()->with('success', 'Stock transfer recorded.');
    }

    public function reserve(Request $request, InventoryStockService $stock)
    {
        $data = $this->movementData($request);
        $stock->reserve($this->companyId(), $data['inventory_id'], $data['warehouse_location_id'], $data['quantity'], $request->user());

        return back()->with('success', 'Stock reservation created.');
    }

    public function releaseReservation(InventoryReservation $reservation, InventoryStockService $stock)
    {
        abort_unless($reservation->company_id === $this->companyId(), 403);
        $stock->release($reservation);

        return back()->with('success', 'Stock reservation released.');
    }

    private function move(Request $request, InventoryStockService $stock, string $operation)
    {
        $data = $this->movementData($request);
        $method = $operation === 'receive' ? 'receive' : 'issue';
        $stock->{$method}(
            $this->companyId(),
            $data['inventory_id'],
            $data['warehouse_location_id'],
            $data['quantity'],
            $data['unit_cost'] ?? null,
            $data['reason'] ?? null,
            $request->user()
        );

        return back()->with('success', 'Stock movement recorded.');
    }

    private function movementData(Request $request, bool $adjustment = false): array
    {
        $companyId = $this->companyId();

        return $request->validate([
            'inventory_id' => ['required', Rule::exists('inventory', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'warehouse_location_id' => [
                'required',
                Rule::exists('warehouse_locations', 'id')->where(
                    fn ($q) => $q->whereIn(
                        'warehouse_id',
                        Warehouse::query()->select('id')->where('company_id', $companyId)
                    )
                ),
            ],
            'quantity' => ['required', 'numeric', $adjustment ? 'numeric' : 'min:0.000001'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'reason' => [$adjustment ? 'required' : 'nullable', 'string', 'max:255'],
        ]);
    }

    private function companyId(): int
    {
        $companyId = session('company_id') ?: Auth::user()?->company_id;
        abort_unless($companyId, 403, 'No active company scope found.');

        return (int) $companyId;
    }

    private function assertLocationsBelongToCompany(int $companyId, array $locationIds): void
    {
        $count = WarehouseLocation::query()
            ->whereIn('id', $locationIds)
            ->whereIn('warehouse_id', Warehouse::query()->select('id')->where('company_id', $companyId))
            ->count();
        abort_unless($count === count(array_unique($locationIds)), 422, 'One or more locations are outside the active company scope.');
    }
}
