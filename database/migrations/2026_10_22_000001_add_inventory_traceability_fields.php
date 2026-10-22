<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->decimal('reorder_point', 20, 6)->default(0)->after('safety_threshold');
            $table->decimal('reorder_quantity', 20, 6)->default(0)->after('reorder_point');
            $table->string('cost_method', 20)->default('weighted_average')->after('reorder_quantity');
            $table->boolean('track_batches')->default(false)->after('cost_method');
            $table->boolean('track_serials')->default(false)->after('track_batches');
            $table->boolean('track_expiry')->default(false)->after('track_serials');
            $table->decimal('tax_rate', 8, 4)->default(0)->after('track_expiry');
            $table->index(['company_id', 'barcode']);
        });

        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained('inventory')->cascadeOnDelete();
            $table->foreignId('warehouse_location_id')->constrained()->cascadeOnDelete();
            $table->string('batch_number', 100)->nullable();
            $table->string('lot_number', 100)->nullable();
            $table->string('serial_number', 150)->nullable();
            $table->decimal('quantity', 20, 6)->default(0);
            $table->decimal('unit_cost', 20, 6)->default(0);
            $table->date('expires_at')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'inventory_id', 'expires_at']);
            $table->unique(['company_id', 'serial_number']);
        });

        Schema::create('inventory_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->nullable()->constrained('inventory')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 50);
            $table->decimal('quantity', 20, 6)->nullable();
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_audit_logs');
        Schema::dropIfExists('inventory_batches');
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'barcode']);
            $table->dropColumn([
                'reorder_point', 'reorder_quantity', 'cost_method', 'track_batches',
                'track_serials', 'track_expiry', 'tax_rate',
            ]);
        });
    }
};
