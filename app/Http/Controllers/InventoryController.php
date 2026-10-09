<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryReservation;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\InventoryStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    //
    public function index()
    {
        return view('inventory');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'sku' => 'required|unique:inventory,sku',
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

        ]);

        $companyId = session('company_id') ?: Auth::user()?->company_id;

        abort_unless($companyId, 403, 'No active company scope found for inventory creation.');

        Inventory::create([
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
        ]);

        return redirect()->route('inventory.view')->with('success', 'Inventory item added successfully!');
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
