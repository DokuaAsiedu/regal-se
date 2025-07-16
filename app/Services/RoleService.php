<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    protected $roleRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function find($id)
    {
        return $this->roleRepository->find($id);
    }

    public function all()
    {
        return $this->roleRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->roleRepository->allQuery($search);
    }

    public function adminRole()
    {
        return $this->roleRepository
            ->allQuery(['code' => 'admin'])
            ->first();
    }

    public function customerRole()
    {
        return $this->roleRepository
            ->allQuery(['code' => 'customer'])
            ->first();
    }
}
