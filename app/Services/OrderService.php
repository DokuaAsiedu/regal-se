<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Services\CartService;
use App\Services\StatusService;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    protected $orderRepository;
    protected $statusService;
    protected $cartService;
    protected $orderItemRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(OrderRepository $orderRepository, StatusService $statusService, CartService $cartService, OrderItemRepository $orderItemRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->statusService = $statusService;
        $this->cartService = $cartService;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function find($id)
    {
        return $this->orderRepository->find($id);
    }

    public function all()
    {
        return $this->orderRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->orderRepository->allQuery($search);
    }

    public function store($input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode();
        } else {
            $this->checkIfCodeExists($input['code']);
        }

        return $this->orderRepository->create($input);
    }

    public function update($id, $input)
    {
        if (isset($input['code'])) {
            $this->checkIfCodeExists($input['code'], $id);
        }

        return $this->orderRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->orderRepository->delete($ids);
    }

    public function validStatuses()
    {
        return collect([
            $this->statusService->pending(),
            $this->statusService->approved(),
            $this->statusService->completed(),
            $this->statusService->declined(),
        ]);
    }

    public function generateCode($code = null)
    {
        $last_id = $this->orderRepository->allQuery()->latest('id')->first('id')->id ?? 0;
        do {
            $padded_id = str_pad($last_id, 5, '0', STR_PAD_LEFT);
            $code = "ORD-$padded_id";
            $last_id++;
        } while ($this->orderRepository->allQuery(['code' => $code])->exists());

        return $code;
    }

    public function checkIfCodeExists($code, $id = null)
    {
        $exists = $this->all()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('code', $code)
            ->exists();

        if ($exists) {
            throw new CustomException(__('Order code already exists'));
        }
    }

    public function placeOrder($input)
    {
        $order_items = $this->cartService->userCart();
        if (empty($order_items)) {
            throw new CustomException('Please add items to your cart before checking out');
        }

        $input['total_amount'] = $this->cartService->calculateCartValue();
        $input['user_id'] = Auth::check() ? Auth::id() : null;
        $input['status_id'] = $this->statusService->pending()->id ?? null;
        $order = $this->store($input);
        $order_id = $order->id;
        $order_items->map(function ($elem) use ($order_id) {
            $elem->order_id = $order_id;
            $elem->unit_price = $elem->price;
            $elem->product_name = $elem->name;

            $this->orderItemRepository->create($elem->toArray());
        });
        // dd($order_items);

        $this->cartService->deleteCart();
    }

    public function isPending($order)
    {
        return $order->status_id == $this->statusService->pending()->id;
    }

    public function approve($order)
    {
        $payload = [
            'status_id' => $this->statusService->approved()->id,
        ];

        $order->update($payload);
    }
}
