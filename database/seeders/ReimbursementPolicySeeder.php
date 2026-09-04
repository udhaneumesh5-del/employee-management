<?php

namespace Database\Seeders;

use App\Models\ReimbursementPolicy;
use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ReimbursementPolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            [
                'name' => 'Travel Policy',
                'expense_type_code' => 'TRAVEL',
                'maximum_amount' => 5000,
                'limit_type' => 'per_claim',
                'receipt_required' => true,
                'exception_allowed' => true,
                'effective_from' => now()->startOfYear(),
                'status' => 'Active',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Food Policy',
                'expense_type_code' => 'FOOD',
                'maximum_amount' => 1000,
                'limit_type' => 'per_day',
                'receipt_required' => true,
                'exception_allowed' => false,
                'effective_from' => now()->startOfYear(),
                'status' => 'Active',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Fuel Policy',
                'expense_type_code' => 'FUEL',
                'maximum_amount' => 5000,
                'limit_type' => 'per_month',
                'receipt_required' => true,
                'exception_allowed' => false,
                'effective_from' => now()->startOfYear(),
                'status' => 'Active',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Hotel Policy',
                'expense_type_code' => 'HOTEL',
                'maximum_amount' => 3000,
                'limit_type' => 'per_day',
                'receipt_required' => true,
                'exception_allowed' => true,
                'effective_from' => now()->startOfYear(),
                'status' => 'Active',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Internet Policy',
                'expense_type_code' => 'INTERNET',
                'maximum_amount' => 1500,
                'limit_type' => 'per_month',
                'receipt_required' => true,
                'exception_allowed' => false,
                'effective_from' => now()->startOfYear(),
                'status' => 'Active',
                'created_by' => 1,
                'updated_by' => 1
            ],
        ];

        foreach ($policies as $policy) {
            $expenseType = ExpenseType::where('code', $policy['expense_type_code'])->first();
            if ($expenseType) {
                ReimbursementPolicy::create([
                    'name' => $policy['name'],
                    'expense_type_id' => $expenseType->id,
                    'maximum_amount' => $policy['maximum_amount'],
                    'limit_type' => $policy['limit_type'],
                    'receipt_required' => $policy['receipt_required'],
                    'exception_allowed' => $policy['exception_allowed'],
                    'effective_from' => $policy['effective_from'],
                    'status' => $policy['status'],
                    'created_by' => $policy['created_by'],
                    'updated_by' => $policy['updated_by']
                ]);
            }
        }
    }
}