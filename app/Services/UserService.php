<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Status;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;
    protected $cartService;

    /**
     * Create a new class instance.
     */
    public function __construct(UserRepository $userRepository, CartService $cartService)
    {
        $this->userRepository = $userRepository;
        $this->cartService = $cartService;
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
        $this->checkIfEmailExists($input['email']);

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

    public function storeAndLogin($input)
    {
        $user = $this->store($input);

        event(new Registered($user));

        Auth::login($user);

        $this->cartService->syncSessionCartToDatabase();

        return $user;
    }
}
