<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });

        Schema::create('inventory_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('symbol', 20);
            $table->decimal('conversion_factor', 20, 6)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'symbol']);
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('company_id')->constrained('inventory_categories')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->after('category_id')->constrained('inventory_units')->nullOnDelete();
            $table->decimal('buying_price', 20, 6)->default(0)->after('unit_price');
            $table->decimal('selling_price', 20, 6)->default(0)->after('buying_price');
            $table->string('barcode', 100)->nullable()->after('sku');
            $table->boolean('is_active')->default(true)->after('safety_threshold');
            $table->index(['company_id', 'is_active']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('payment_terms', 50)->default('cash');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['company_id', 'name']);
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->string('purchase_number', 100);
            $table->date('purchase_date');
            $table->string('status', 30)->default('received');
            $table->decimal('subtotal', 20, 2)->default(0);
            $table->decimal('tax_amount', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);
            $table->string('payment_status', 30)->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'purchase_number']);
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained('inventory')->restrictOnDelete();
            $table->decimal('quantity', 20, 6);
            $table->decimal('unit_cost', 20, 6);
            $table->decimal('line_total', 20, 2);
            $table->timestamps();
        });

        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type', 20);
            $table->string('code', 50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });

        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('finance_category_id')->constrained()->restrictOnDelete();
            $table->nullableMorphs('source');
            $table->string('type', 20);
            $table->decimal('amount', 20, 2);
            $table->date('transaction_date');
            $table->string('reference', 100);
            $table->string('status', 30)->default('posted');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('finance_categories');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('suppliers');
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['category_id', 'unit_id', 'buying_price', 'selling_price', 'barcode', 'is_active']);
        });
        Schema::dropIfExists('inventory_units');
        Schema::dropIfExists('inventory_categories');
    }
};
