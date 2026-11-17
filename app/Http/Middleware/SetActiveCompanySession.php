<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveCompanySession
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $companyId = session('company_id');

            if ($companyId && $user->belongsToCompany((int) $companyId)) {
                $company = Company::where('is_active', true)->find($companyId);
                if ($company) {
                    $this->setCompany($company);
                } else {
                    $fallback = $user->availableCompanies()->first() ?: $user->company;
                    $this->setCompany($fallback);
                }
            } else {
                $fallback = $user->availableCompanies()->first() ?: $user->company;
                if ($fallback) {
                    $this->setCompany($fallback);
                } else {
                    session()->forget(['company_id', 'active_company_id', 'company_name', 'active_company_name']);
                }
            }
        }

        return $next($request);
    }

    private function setCompany(?Company $company): void
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
