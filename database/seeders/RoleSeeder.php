<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'code' => 'admin',
                'name' => 'Admin',
                'status_id' => $status_id,
            ],
            [
                'code' => 'customer',
                'name' => 'Customer',
                'status_id' => $status_id,
            ],
        ];

        foreach ($data as $item) {
            Role::create($item);
        }
    }
}
