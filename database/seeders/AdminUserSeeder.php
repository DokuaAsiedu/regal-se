<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status_id = Status::where('name', 'active')
            ->value('id');

        $admin_role_id = $this->roleService
            ->adminRole()
            ->id ?? null;

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('regal.se#2711'),
            'email_verified_at' => now(),
            'status_id' => $status_id,
            'is_admin' => true,
            'role_id' => $admin_role_id,
        ]);
    }
}
