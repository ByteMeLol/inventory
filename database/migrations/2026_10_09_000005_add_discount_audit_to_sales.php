<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount_amount', 20, 2)->default(0)->after('unit_price');
            $table->string('discount_reason')->nullable()->after('discount_amount');
            $table->foreignId('discount_given_by')->nullable()->after('discount_reason')->constrained('users')->nullOnDelete();
            $table->index(['company_id', 'discount_given_by']);
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['discount_given_by']);
            $table->dropIndex(['company_id', 'discount_given_by']);
            $table->dropColumn(['discount_amount', 'discount_reason', 'discount_given_by']);
        });
    }
};
