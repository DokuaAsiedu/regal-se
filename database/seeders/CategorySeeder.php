<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status_id = Status::where('name', 'active')
            ->value('id');

        $data = [
            [
                'name' => 'Kitchen Appliances',
                'code' => 'CAT001',
                'description' => 'Electric-powered kitchen devices.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Entertainment Devices',
                'code' => 'CAT002',
                'description' => 'Home entertainment and media gadgets.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Cleaning Equipment',
                'code' => 'CAT003',
                'description' => 'Electrical tools for household cleaning.',
                'status_id' => $status_id,
            ],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
