<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'role_id',

    ];

    /**
     * Get the company that the user belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_memberships')
            ->withPivot(['role_id', 'status', 'joined_at'])
            ->wherePivot('status', 'active')
            ->withTimestamps();
    }

    public function belongsToCompany(int $companyId): bool
    {
        return $this->isSuperAdmin() || $this->companies()->whereKey($companyId)->exists();
    }

    public function isSuperAdmin(): bool
    {
        $roleName = strtolower(str_replace(['_', '-'], ' ', $this->role?->name ?? ''));

        return $this->role_id == 1 || $roleName === 'super admin';
    }

    public function canManageUsers(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $companyId = session('company_id');
        $membership = $companyId
            ? $this->companies()->whereKey($companyId)->first()?->pivot
            : null;
        $roleName = strtolower(str_replace(['_', '-'], ' ', $this->role?->name ?? ''));

        return in_array((int) ($membership?->role_id ?? $this->role_id), [1, 3], true)
            || in_array($roleName, ['super admin', 'admin'], true);
    }

     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    // Keep your hidden and casts attributes below if they exist...
}