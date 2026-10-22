<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    //
    public function store(Request $request){
        dd($request->all());
    }
    public function create(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('login')
                ->withInput($request->except('password'))
                ->withErrors($validator);
        }

        $credentials = $validator->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $company = $user?->companies()->where('is_active', true)->first();

            if ($company) {
                session([
                    'company_id' => $company->id,
                    'active_company_id' => $company->id,
                    'company_name' => $company->name,
                    'active_company_name' => $company->name,
                ]);
            } elseif ($user->isSuperAdmin()) {
                $company = Company::where('is_active', true)->first();

                if ($company) {
                    session([
                        'company_id' => $company->id,
                        'active_company_id' => $company->id,
                        'company_name' => $company->name,
                        'active_company_name' => $company->name,
                    ]);
                }
            }

            return redirect('/home')->with('success', 'Logged in successfully.');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);

       
    }
    public function destroy(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
