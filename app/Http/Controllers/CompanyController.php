<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Store a new company owned by the authenticated user.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $company = DB::transaction(function () use ($user, $validated) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug ?: 'company';
            $suffix = 2;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $suffix++;
            }

            $company = Company::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'owner_id' => $user->id,
                'is_active' => true,
                'settings' => [
                    'currency' => $validated['currency'] ?? 'TZS',
                    'tax_rate' => 18.0,
                    'low_stock_limit' => 5,
                    'out_of_stock_policy' => 'lock',
                    'timezone' => 'Africa/Dar_es_Salaam',
                    'country' => 'Tanzania',
                    'branch_name' => $validated['name'] . ' Main Branch',
                    'company_phone' => $validated['phone'] ?? $user->phone ?? '',
                ],
            ]);

            // Attach user with admin role
            $company->members()->attach($user->id, [
                'role_id' => $user->role_id ?: 2,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            if (! $user->company_id) {
                $user->update(['company_id' => $company->id]);
            }

            return $company;
        });

        // Set the active session scope to the new company
        session([
            'company_id' => $company->id,
            'active_company_id' => $company->id,
            'company_name' => $company->name,
            'active_company_name' => $company->name,
        ]);

        return redirect()->back()->with('success', __('Company ":name" created successfully and selected as active workspace.', ['name' => $company->name]));
    }

    /**
     * Switch active company scope in session.
     */
    public function switchCompany($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user, 401);

        $company = Company::where('is_active', true)->findOrFail($id);

        if (! $user->belongsToCompany($company->id)) {
            abort(403, 'Unauthorized company access request.');
        }

        session([
            'company_id' => $company->id,
            'active_company_id' => $company->id,
            'company_name' => $company->name,
            'active_company_name' => $company->name,
        ]);

        return redirect()->back()->with('success', __('Active company scope switched to: :name', ['name' => $company->name]));
    }
}
