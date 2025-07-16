<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Status;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function all()
    {
        return $this->userRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->userRepository->allQuery($search);
    }

    public function store($input)
    {
        $this->checkIfEmailExists($input['name']);

        return $this->userRepository->create($input);
    }

    public function update($id, $input)
    {
        $this->checkIfEmailExists($input['email'], $id);

        return $this->userRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->userRepository->delete($ids);
    }

    public function validStatuses()
    {
        return Status::whereIn('code', ['active', 'inactive', 'suspended'])
            ->get();
    }

    public function checkIfEmailExists($email, $id = null)
    {
        $exists = $this->allQuery()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('email', $email)
            ->exists();

        if ($exists) {
            throw new CustomException('Email already exists');
        }
    }
}
