<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user?->canManageUsers(), 403, 'Unauthorized user management request.');

        $requestedCompanyId = $request->query('company_id');
        $selectedCompanyId = null;

        if ($requestedCompanyId && $user->belongsToCompany((int) $requestedCompanyId)) {
            $selectedCompanyId = (int) $requestedCompanyId;
        } elseif ($requestedCompanyId === null && session()->has('company_id') && $user->belongsToCompany((int) session('company_id'))) {
            $selectedCompanyId = (int) session('company_id');
        }

        $companies = $user->availableCompanies();
        $roles = Role::whereNotIn('name', ['super admin'])->orderBy('name')->get();
        $staffMembers = $user->staffQuery($selectedCompanyId)->paginate(10);

        return view('staff', [
            'staffMembers' => $staffMembers,
            'roles' => $roles,
            'companies' => $companies,
            'selectedCompanyId' => $selectedCompanyId,
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
        abort_unless($user->belongsToCompany($assignedCompanyId), 403, 'You do not have access to assign staff to this company.');

        $temporaryPassword = Str::upper($validatedData['last_name']);

        DB::transaction(function () use ($user, $assignedCompanyId, $validatedData, $temporaryPassword): void {
            $newUser = User::create([
                'creator_id' => $user->id,
                'company_id' => $assignedCompanyId,
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone' => $validatedData['phone'] ?? null,
                'email' => $validatedData['email'],
                'role_id' => $validatedData['role_id'],
                'password' => Hash::make($temporaryPassword),
            ]);

            $newUser->companies()->syncWithoutDetaching([
                $assignedCompanyId => [
                    'role_id' => $validatedData['role_id'],
                    'status' => 'active',
                    'joined_at' => now(),
                ],
            ]);
        });

        return redirect()->back()->with('success', __('Staff account created and assigned successfully. Temporary password: :password', ['password' => $temporaryPassword]));
    }

    public function update(Request $request, User $staff)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user?->canManageUsers(), 403, 'Unauthorized user management request.');
        abort_if($staff->id === $user->id, 403, 'Please update your own profile through My Profile settings.');

        // Verify this staff member belongs to current user's scope
        $canManageStaff = ($staff->creator_id === $user->id)
            || ($staff->company_id && $user->belongsToCompany($staff->company_id));

        abort_unless($canManageStaff, 403, 'You do not have permission to manage this staff account.');

        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email,' . $staff->id],
            'role_id' => ['required', 'exists:roles,id'],
            'company_id' => ['required', 'exists:companies,id'],
        ]);

        $targetCompanyId = (int) $validatedData['company_id'];
        abort_unless($user->belongsToCompany($targetCompanyId), 403, 'You do not have access to assign staff to this company.');

        DB::transaction(function () use ($staff, $targetCompanyId, $validatedData): void {
            $staff->update([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone' => $validatedData['phone'] ?? null,
                'email' => $validatedData['email'],
                'role_id' => $validatedData['role_id'],
                'company_id' => $targetCompanyId,
            ]);

            $staff->companies()->sync([
                $targetCompanyId => [
                    'role_id' => $validatedData['role_id'],
                    'status' => 'active',
                    'joined_at' => now(),
                ],
            ]);
        });

        return redirect()->back()->with('success', 'Staff account updated and assigned to company successfully.');
    }

    public function destroy(User $staff)
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user?->canManageUsers(), 403, 'Unauthorized user deletion request.');
        abort_if($staff->id === $user->id, 403, 'You cannot delete your own account.');

        $canManageStaff = ($staff->creator_id === $user->id)
            || ($staff->company_id && $user->belongsToCompany($staff->company_id));

        abort_unless($canManageStaff, 403, 'You do not have permission to delete this staff account.');

        DB::transaction(function () use ($staff): void {
            $staff->companies()->detach();
            $staff->delete();
        });

        return redirect()->back()->with('success', 'Staff account removed successfully.');
    }
}
