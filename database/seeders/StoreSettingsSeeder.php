<?php

namespace Database\Seeders;

use App\Models\StoreSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'currency_code',
                'name' => 'Currency Code',
                'value' => 'GHS',
            ],
            [
                'code' => 'currency_name',
                'name' => 'Currency Name',
                'value' => 'Ghanaian Cedi',
            ],
            [
                'code' => 'currency_symbol',
                'name' => 'Currency Symbol',
                'value' => 'GH₵',
            ],
            [
                'code' => 'repayment_months',
                'name' => 'Repayment Months',
                'value' => 4,
            ],
        ];

        foreach ($data as $item) {
            StoreSettings::create($item);
        }
    }
}
