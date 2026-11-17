<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function create()
    {
        return view('signup');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('signup.create')
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors($validator);
        }

        $validatedData = $validator->validated();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $roleId = $adminRole->id;

        DB::transaction(function () use ($validatedData, $roleId): void {
            $baseSlug = Str::slug($validatedData['company_name']);
            $slug = $baseSlug ?: 'company';
            $suffix = 2;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $suffix++;
            }

            // Create root account owner user
            $user = User::create([
                'creator_id' => null,
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone' => $validatedData['phone'],
                'role_id' => $roleId,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Create first company owned by this user
            $company = Company::create([
                'name' => $validatedData['company_name'],
                'slug' => $slug,
                'owner_id' => $user->id,
                'is_active' => true,
                'settings' => [
                    'currency' => 'TZS',
                    'tax_rate' => 18.0,
                    'low_stock_limit' => 5,
                    'out_of_stock_policy' => 'lock',
                    'timezone' => 'Africa/Dar_es_Salaam',
                    'country' => 'Tanzania',
                    'branch_name' => $validatedData['company_name'] . ' HQ',
                    'company_phone' => $validatedData['phone'],
                ],
            ]);

            // Link user to primary company
            $user->update(['company_id' => $company->id]);

            // Attach user to company memberships
            $user->companies()->attach($company->id, [
                'role_id' => $roleId,
                'status' => 'active',
                'joined_at' => now(),
            ]);
        });

        return redirect('/')->with('success', 'Account created successfully! Please sign in with your credentials.');
    }
}
