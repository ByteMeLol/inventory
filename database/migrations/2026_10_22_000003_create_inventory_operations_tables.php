<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained('inventory')->cascadeOnDelete();
            $table->foreignId('warehouse_location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30);
            $table->string('status', 20)->default('open');
            $table->text('message');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status', 'type']);
        });

        Schema::create('inventory_return_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained('inventory')->restrictOnDelete();
            $table->foreignId('warehouse_location_id')->constrained()->restrictOnDelete();
            $table->string('direction', 20);
            $table->string('reference_number', 100);
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity', 20, 6);
            $table->decimal('unit_cost', 20, 6)->default(0);
            $table->string('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['company_id', 'direction', 'created_at']);
        });

        Schema::create('inventory_stocktakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_location_id')->constrained()->restrictOnDelete();
            $table->string('reference_number', 100);
            $table->string('status', 20)->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'reference_number']);
        });

        Schema::create('inventory_stocktake_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocktake_id')->constrained('inventory_stocktakes')->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained('inventory')->restrictOnDelete();
            $table->decimal('expected_quantity', 20, 6)->default(0);
            $table->decimal('counted_quantity', 20, 6)->default(0);
            $table->decimal('variance', 20, 6)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->unique(['stocktake_id', 'inventory_id']);
        });

        Schema::create('inventory_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->morphs('approvable');
            $table->string('action', 30);
            $table->string('status', 20)->default('pending');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comment')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_approvals');
        Schema::dropIfExists('inventory_stocktake_lines');
        Schema::dropIfExists('inventory_stocktakes');
        Schema::dropIfExists('inventory_return_lines');
        Schema::dropIfExists('inventory_alerts');
    }
};
