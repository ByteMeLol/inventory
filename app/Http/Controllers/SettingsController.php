<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InventoryCategory;
use App\Models\InventoryUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const DEFAULT_SETTINGS = [
        'currency' => 'TZS',
        'tax_rate' => 18.0,
        'low_stock_limit' => 5,
        'out_of_stock_policy' => 'lock',
        'timezone' => 'Africa/Dar_es_Salaam',
        'country' => 'Tanzania',
        'branch_name' => 'Dar es Salaam HQ',
        'branch_code' => 'TZ-DSM-001',
        'company_phone' => '+255 754 000 000',
    ];

    public function edit(Request $request): View
    {
        $company = $this->resolveActiveCompany($request);

        return view('settings', [
            'company' => $company,
            'settings' => $this->settingsForCompany($company),
            'inventoryCategories' => InventoryCategory::where('company_id', $company->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'inventoryUnits' => InventoryUnit::where('company_id', $company->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $company = $this->resolveActiveCompany($request);

        $validated = $request->validate([
            'currency' => ['required', 'string', 'size:3'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'low_stock_limit' => ['required', 'integer', 'min:0'],
            'out_of_stock_policy' => ['required', 'in:lock,allow'],
            'timezone' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'branch_name' => ['required', 'string', 'max:255'],
            'branch_code' => ['required', 'string', 'max:50'],
            'company_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $company->settings = array_merge($this->settingsForCompany($company), $validated);
        $company->save();

        return redirect()
            ->route('settings.edit')
            ->with('success', 'Tanzania settings saved for ' . $company->name . '.');
    }

    private function resolveActiveCompany(Request $request): Company
    {
        $companyId = $request->session()->get('company_id');

        abort_unless($companyId, 403, 'No active company selected.');

        return Company::findOrFail($companyId);
    }

    private function settingsForCompany(Company $company): array
    {
        return array_merge(self::DEFAULT_SETTINGS, $company->settings ?? []);
    }
}