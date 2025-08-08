<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'success',
                'name' => 'Success',
            ],
            [
                'code' => 'failed',
                'name' => 'Failed',
            ],
            [
                'code' => 'abandoned',
                'name' => 'Abandoned',
            ],
            [
                'code' => 'cancelled',
                'name' => 'Cancelled',
            ],
            [
                'code' => 'invalid',
                'name' => 'Invalid',
            ],
            [
                'code' => 'unknown',
                'name' => 'Unknown',
            ],
        ];

        foreach ($data as $item) {
            Status::create($item);
        }
    }
}
