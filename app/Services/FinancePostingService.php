<?php

namespace App\Services;

use App\Models\FinanceCategory;
use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Model;

class FinancePostingService
{
    public function post(
        int $companyId,
        string $categoryCode,
        string $categoryName,
        string $categoryType,
        string $type,
        float $amount,
        string $reference,
        ?Model $source = null,
        ?string $description = null,
        ?string $paymentMethod = null
    ): FinancialTransaction {
        $category = FinanceCategory::firstOrCreate(
            ['company_id' => $companyId, 'code' => $categoryCode],
            ['name' => $categoryName, 'type' => $categoryType, 'is_active' => true]
        );

        return FinancialTransaction::create([
            'company_id' => $companyId,
            'finance_category_id' => $category->id,
            'source_type' => $source ? $source::class : null,
            'source_id' => $source?->getKey(),
            'type' => $type,
            'payment_method' => $paymentMethod,
            'amount' => $amount,
            'transaction_date' => now()->toDateString(),
            'reference' => $reference,
            'description' => $description,
        ]);
    }
}
