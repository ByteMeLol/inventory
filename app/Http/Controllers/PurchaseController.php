<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\FinancePostingService;
use App\Services\InventoryStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    public function index()
    {
        $companyId = $this->companyId();

        return view('purchases', [
            'purchases' => Purchase::with('supplier')->where('company_id', $companyId)->latest()->paginate(15),
            'suppliers' => Supplier::where('company_id', $companyId)->where('is_active', true)->orderBy('name')->get(),
            'inventoryItems' => Inventory::where('company_id', $companyId)->where('is_active', true)->orderBy('description')->get(),
            'locations' => WarehouseLocation::with('warehouse')
                ->whereIn('warehouse_id', Warehouse::query()->select('id')->where('company_id', $companyId))
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
        ]);
    }

    public function store(Request $request, InventoryStockService $stock, FinancePostingService $finance)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'supplier_id' => ['required', Rule::exists('suppliers', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'purchase_number' => ['required', 'string', 'max:100', Rule::unique('purchases')->where(fn ($q) => $q->where('company_id', $companyId))],
            'purchase_date' => ['required', 'date'],
            'warehouse_location_id' => [
                'required',
                Rule::exists('warehouse_locations', 'id')->where(
                    fn ($q) => $q->whereIn('warehouse_id', Warehouse::query()->select('id')->where('company_id', $companyId))
                ),
            ],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid', 'partial'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventory_id' => ['required', Rule::exists('inventory', 'id')->where(fn ($q) => $q->where('company_id', $companyId))],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_cost' => ['required', 'numeric', 'gte:0'],
        ]);

        $purchase = DB::transaction(function () use ($data, $companyId, $stock, $finance) {
            $subtotal = collect($data['items'])->sum(fn ($item) => $item['quantity'] * $item['unit_cost']);
            $purchase = Purchase::create([
                'company_id' => $companyId,
                'supplier_id' => $data['supplier_id'],
                'purchase_number' => $data['purchase_number'],
                'purchase_date' => $data['purchase_date'],
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'payment_status' => $data['payment_status'],
            ]);
            foreach ($data['items'] as $item) {
                $purchase->items()->create([
                    'inventory_id' => $item['inventory_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $item['quantity'] * $item['unit_cost'],
                ]);
                $stock->receive($companyId, $item['inventory_id'], $data['warehouse_location_id'], $item['quantity'], $item['unit_cost'], 'Purchase '.$purchase->purchase_number, auth()->user());
            }
            $finance->post($companyId, 'PURCHASES', 'Inventory purchases', 'expense', 'expense', $subtotal, $purchase->purchase_number, $purchase);

            return $purchase;
        });

        return redirect()->route('purchases.index')->with('success', "Purchase {$purchase->purchase_number} recorded.");
    }

    public function storeSupplier(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate(['name' => 'required|string|max:255', 'contact_name' => 'nullable|string|max:255', 'phone' => 'nullable|string|max:50', 'email' => 'nullable|email', 'tax_id' => 'nullable|string|max:100', 'address' => 'nullable|string', 'payment_terms' => 'required|string|max:50']);
        Supplier::create([...$data, 'company_id' => $companyId]);

        return back()->with('success', 'Supplier created.');
    }

    private function companyId(): int
    {
        $companyId = session('company_id') ?: auth()->user()?->company_id;
        abort_unless($companyId, 403, 'No active company scope found.');

        return (int) $companyId;
    }
}
