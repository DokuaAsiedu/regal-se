<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Order;
use App\Models\Status;
use App\Repositories\PaymentRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    protected $paymentRepository;
    protected $cartService;
    protected $roleService;

    /**
     * Create a new class instance.
     */
    public function __construct(PaymentRepository $paymentRepository, CartService $cartService, RoleService $roleService)
    {
        $this->paymentRepository = $paymentRepository;
        $this->cartService = $cartService;
        $this->roleService = $roleService;
    }

    public function find($id)
    {
        return $this->paymentRepository->find($id);
    }

    public function all()
    {
        return $this->paymentRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->paymentRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->paymentRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->paymentRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->paymentRepository->delete($ids);
    }
}
