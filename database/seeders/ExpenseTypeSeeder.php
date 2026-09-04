<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $expenseTypes = [
            ['name' => 'Travel', 'code' => 'TRAVEL', 'description' => 'Travel expenses'],
            ['name' => 'Food', 'code' => 'FOOD', 'description' => 'Food and meal expenses'],
            ['name' => 'Hotel', 'code' => 'HOTEL', 'description' => 'Hotel and accommodation expenses'],
            ['name' => 'Fuel', 'code' => 'FUEL', 'description' => 'Fuel and transportation expenses'],
            ['name' => 'Parking', 'code' => 'PARKING', 'description' => 'Parking expenses'],
            ['name' => 'Toll', 'code' => 'TOLL', 'description' => 'Toll expenses'],
            ['name' => 'Internet', 'code' => 'INTERNET', 'description' => 'Internet and broadband expenses'],
            ['name' => 'Mobile', 'code' => 'MOBILE', 'description' => 'Mobile phone expenses'],
            ['name' => 'Office Supplies', 'code' => 'OFFICE_SUPPLIES', 'description' => 'Office supplies and stationery'],
            ['name' => 'Printing', 'code' => 'PRINTING', 'description' => 'Printing and photocopy expenses'],
            ['name' => 'Other', 'code' => 'OTHER', 'description' => 'Other miscellaneous expenses'],
        ];

        foreach ($expenseTypes as $type) {
            ExpenseType::create($type);
        }
    }
}