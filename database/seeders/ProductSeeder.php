<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
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
                'name' => 'Microwave Oven',
                'description' => '800W digital microwave for quick meals.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Smart TV 55"',
                'description' => '4K UHD Smart TV with HDR support.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Vacuum Cleaner',
                'description' => 'Bagless upright vacuum for deep cleaning.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Air Conditioner',
                'description' => '1.5HP split AC with energy-saving mode.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Blender',
                'description' => 'Multi-speed blender with glass jar.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Washing Machine',
                'description' => 'Front-load washer with 8kg capacity.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Electric Kettle',
                'description' => '1.7L fast boil stainless kettle.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Home Theater System',
                'description' => '5.1 surround sound with Bluetooth.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Electric Iron',
                'description' => 'Steam iron with non-stick soleplate.',
                'status_id' => $status_id,
            ],
            [
                'name' => 'Ceiling Fan',
                'description' => 'Remote-controlled 3-speed ceiling fan.',
                'status_id' => $status_id,
            ],
        ];

        foreach ($data as $item) {
            Product::factory()->create($item);
        }
    }
}
