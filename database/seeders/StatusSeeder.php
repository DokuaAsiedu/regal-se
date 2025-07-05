<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'active',
                'name' => 'Active',
            ],
            [
                'code' => 'inactive',
                'name' => 'Inactive',
            ],
            [
                'code' => 'pending',
                'name' => 'Pending',
            ],
            [
                'code' => 'approved',
                'name' => 'Approved',
            ],
            [
                'code' => 'completed',
                'name' => 'Completed',
            ],
            [
                'code' => 'declined',
                'name' => 'Declined',
            ],
            [
                'code' => 'suspended',
                'name' => 'Suspended',
            ],
        ];

        foreach ($data as $item) {
            Status::create($item);
        }
    }
}
