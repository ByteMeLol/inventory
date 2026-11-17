<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'creator_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Primary company assigned to the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User who created this staff account (null for account owners).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Staff accounts created by this user.
     */
    public function createdStaff(): HasMany
    {
        return $this->hasMany(User::class, 'creator_id');
    }

    /**
     * Companies owned by this user.
     */
    public function ownedCompanies(): HasMany
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    /**
     * Companies where this user has active membership.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_memberships')
            ->withPivot(['role_id', 'status', 'joined_at'])
            ->wherePivot('status', 'active')
            ->withTimestamps();
    }

    /**
     * Get all companies that this user owns or is an active member of.
     */
    public function availableCompanies()
    {
        return Company::where('is_active', true)
            ->where(function ($query) {
                $query->where('owner_id', $this->id)
                    ->orWhereHas('members', function ($q) {
                        $q->where('users.id', $this->id)->where('status', 'active');
                    });
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Check if user owns or belongs to the specified company.
     */
    public function belongsToCompany(int $companyId): bool
    {
        return $this->availableCompanies()->contains('id', $companyId);
    }

    public function isSuperAdmin(): bool
    {
        $roleName = strtolower(str_replace(['_', '-'], ' ', $this->role?->name ?? ''));

        return $this->role_id == 1 || $roleName === 'super admin';
    }

    /**
     * Check if user can manage users / staff.
     */
    public function canManageUsers(): bool
    {
        if ($this->ownedCompanies()->exists()) {
            return true;
        }

        $companyId = session('company_id');
        $membership = $companyId
            ? $this->companies()->whereKey($companyId)->first()?->pivot
            : null;
        $roleName = strtolower(str_replace(['_', '-'], ' ', $this->role?->name ?? ''));

        return in_array((int) ($membership?->role_id ?? $this->role_id), [1, 2, 3], true)
            || in_array($roleName, ['super admin', 'admin', 'manager', 'owner'], true);
    }

    /**
     * Query builder for staff belonging to this user or their companies.
     */
    public function staffQuery(?int $companyId = null)
    {
        $myCompanyIds = $this->availableCompanies()->pluck('id');

        return User::with(['company', 'role'])
            ->where('id', '!=', $this->id)
            ->where(function ($query) use ($myCompanyIds) {
                $query->where('creator_id', $this->id)
                    ->orWhereIn('company_id', $myCompanyIds);
            })
            ->where(function ($query) {
                // Do not display other company owners in staff list
                $otherOwnerIds = Company::where('owner_id', '!=', $this->id)->pluck('owner_id')->filter()->all();
                $query->where('creator_id', $this->id);
                if (! empty($otherOwnerIds)) {
                    $query->orWhereNotIn('id', $otherOwnerIds);
                }
            })
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('created_at', 'desc');
    }
}
