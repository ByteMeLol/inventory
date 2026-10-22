<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user?->canManageUsers(), 403, 'Unauthorized user management request.');

        $companyId = session('company_id');
        $companies = $user->isSuperAdmin()
            ? Company::orderBy('name')->get()
            : Company::whereKey($companyId)->get();
        $staffMembers = User::with(['company', 'role'])
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('company_id', $companyId))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('staff', [
            'staffMembers' => $staffMembers,
            'roles' => Role::orderBy('name')->get(),
            'companies' => $companies,
            'canManageUsers' => true,
            'currentUser' => $user,
        ]);
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user?->canManageUsers(), 403, 'Unauthorized user creation request.');

        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role_id' => ['required', 'exists:roles,id'],
            'company_id' => ['required', 'exists:companies,id'],
        ]);

        $assignedCompanyId = (int) $validatedData['company_id'];
        abort_unless($user->isSuperAdmin() || $user->belongsToCompany($assignedCompanyId), 403);

        $temporaryPassword = Str::upper($validatedData['last_name']);

        DB::transaction(function () use ($assignedCompanyId, $validatedData, $temporaryPassword): void {
            $newUser = User::create([
                'company_id' => $assignedCompanyId,
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone' => $validatedData['phone'] ?? null,
                'email' => $validatedData['email'],
                'role_id' => $validatedData['role_id'],
                'password' => Hash::make($temporaryPassword),
            ]);
            $newUser->companies()->syncWithoutDetaching([
                $assignedCompanyId => ['role_id' => $validatedData['role_id'], 'status' => 'active', 'joined_at' => now()],
            ]);
        });

        return redirect()->back()->with('success', 'Staff account created. Temporary password set to the uppercase last name: ' . $temporaryPassword);
    }
}