<?php

namespace Database\Seeders;

use App\Models\StoreSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DownPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'down_payment_percentage',
                'name' => 'Down Payment Percentage',
                'value' => 35,
            ],
        ];

        foreach ($data as $item) {
            StoreSettings::create($item);
        }
    }
}
