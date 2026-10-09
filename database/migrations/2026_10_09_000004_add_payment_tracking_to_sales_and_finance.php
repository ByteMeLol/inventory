<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('payment_method', 30)->nullable()->after('billing_terms');
            $table->string('payment_reference', 100)->nullable()->after('payment_method');
            $table->index(['company_id', 'payment_method']);
        });

        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->string('payment_method', 30)->nullable()->after('type');
            $table->index(['company_id', 'payment_method']);
        });
    }

    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'payment_method']);
            $table->dropColumn('payment_method');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'payment_method']);
            $table->dropColumn(['payment_method', 'payment_reference']);
        });
    }
};
