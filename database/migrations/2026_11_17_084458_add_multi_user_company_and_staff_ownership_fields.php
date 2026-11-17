<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('companies', 'owner_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('users', 'creator_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        // Add standard roles if not present
        $now = now();
        $roles = [
            ['name' => 'super admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'manager', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'staff', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($roles as $role) {
            if (! DB::table('roles')->where('name', $role['name'])->exists()) {
                DB::table('roles')->insert($role);
            }
        }

        // Backfill owner_id for existing companies
        $companies = DB::table('companies')->get();
        foreach ($companies as $company) {
            $ownerId = DB::table('company_memberships')
                ->where('company_id', $company->id)
                ->where('status', 'active')
                ->value('user_id');

            if (! $ownerId) {
                $ownerId = DB::table('users')
                    ->where('company_id', $company->id)
                    ->value('id');
            }

            if (! $ownerId) {
                $ownerId = DB::table('users')->orderBy('id')->value('id');
            }

            if ($ownerId) {
                DB::table('companies')->where('id', $company->id)->update(['owner_id' => $ownerId]);
                // Ensure owner has membership record
                if (! DB::table('company_memberships')->where('company_id', $company->id)->where('user_id', $ownerId)->exists()) {
                    $ownerRole = DB::table('roles')->where('name', 'admin')->value('id') ?: 2;
                    DB::table('company_memberships')->insert([
                        'company_id' => $company->id,
                        'user_id' => $ownerId,
                        'role_id' => $ownerRole,
                        'status' => 'active',
                        'joined_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'creator_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('creator_id');
            });
        }

        if (Schema::hasColumn('companies', 'owner_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('owner_id');
            });
        }
    }
};
