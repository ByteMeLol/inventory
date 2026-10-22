<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyScopeConttroller extends Controller
{
    //
    public function switchCompany($id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

    //dd($id);
        $company = Company::where('is_active', true)->findOrFail($id);

        if (! $user?->belongsToCompany($company->id)) {
            abort(403, 'Unauthorized scope alteration request.');
        }

        // Store both variables safely inside the session context
        session([
            'company_id'   => $company->id,
            'company_name' => $company->name,
        ]);

        return redirect()->back()->with('success', 'Active company scope switched to: ' . $company->name);
    }
    
}
