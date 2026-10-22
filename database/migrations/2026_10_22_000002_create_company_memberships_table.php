<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 20)->default('active');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });

        DB::table('users')
            ->whereNotNull('company_id')
            ->select(['id', 'company_id', 'role_id', 'created_at'])
            ->orderBy('id')
            ->each(function (object $user): void {
                DB::table('company_memberships')->insert([
                    'company_id' => $user->company_id,
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'status' => 'active',
                    'joined_at' => $user->created_at,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_memberships');
    }
};
