<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    public function category(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('inventory_categories')->where(fn ($q) => $q->where('company_id', $companyId))],
            'description' => ['nullable', 'string'],
        ]);
        InventoryCategory::create([...$data, 'company_id' => $companyId]);

        return back()->with('success', 'Inventory category created.');
    }

    public function unit(Request $request)
    {
        $companyId = $this->companyId();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:20', Rule::unique('inventory_units')->where(fn ($q) => $q->where('company_id', $companyId))],
            'conversion_factor' => ['required', 'numeric', 'gt:0'],
        ]);
        InventoryUnit::create([...$data, 'company_id' => $companyId]);

        return back()->with('success', 'Inventory unit created.');
    }

    private function companyId(): int
    {
        $companyId = session('company_id') ?: auth()->user()?->company_id;
        abort_unless($companyId, 403, 'No active company scope found.');

        return (int) $companyId;
    }
}
