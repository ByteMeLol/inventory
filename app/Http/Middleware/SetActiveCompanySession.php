<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveCompanySession
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // If the user belongs to a specific company, lock their session to it.
            if ($user->isSuperAdmin()) {
                if (! session()->has('company_id')) {
                    $firstCompany = \App\Models\Company::where('is_active', true)->first();
                    if ($firstCompany) {
                        $this->setCompany($firstCompany);
                    }
                }
            } elseif ($user->companies()->whereKey(session('company_id'))->exists()) {
                $company = $user->companies()->find(session('company_id'));
                $this->setCompany($company);
            } elseif ($user->company_id) {
                $company = $user->company;
                $this->setCompany($company);
            } else {
                session()->forget(['company_id', 'active_company_id', 'company_name', 'active_company_name']);
            }
        }

        return $next($request);
    }

    private function setCompany(?\App\Models\Company $company): void
    {
        if ($company) {
                session([
                    'company_id' => $company->id,
                    'active_company_id' => $company->id,
                    'company_name' => $company->name,
                    'active_company_name' => $company->name,
                ]);
        }
    }
}