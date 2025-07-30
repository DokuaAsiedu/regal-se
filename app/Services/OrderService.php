<?php

namespace App\Services;

use App\Enums\PaymentPlan;
use App\Exceptions\CustomException;
use App\Models\Order;
use App\Notifications\OrderApproved;
use App\Notifications\OrderPlaced;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Services\CartService;
use App\Services\StatusService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    protected $orderRepository;
    protected $statusService;
    protected $cartService;
    protected $orderItemRepository;
    protected $userService;
    protected $paymentService;
    protected $storeSettingsService;

    /**
     * Create a new class instance.
     */
    public function __construct(
        OrderRepository $orderRepository,
        StatusService $statusService,
        CartService $cartService,
        OrderItemRepository $orderItemRepository,
        UserService $userService,
        PaymentService $paymentService,
        StoreSettingsService $storeSettingsService,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->statusService = $statusService;
        $this->cartService = $cartService;
        $this->orderItemRepository = $orderItemRepository;
        $this->userService = $userService;
        $this->paymentService = $paymentService;
        $this->storeSettingsService = $storeSettingsService;
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

        $this->cartService->deleteCart();

        // send notification
        $this->sendOrderPlacedNotification($order);
    }

    public function sendOrderPlacedNotification(Order $order)
    {
        // notify customer via mail
        Notification::route('mail', $order->customer_email)
            ->notify(new OrderPlaced($order));

        // if customer is a user send database notification
        if ($order->user) {
            Notification::send($order->user, new OrderPlaced($order));
        }

        // notify admins
        $admins = $this->userService->admins()->get();
        Notification::send($admins, new OrderPlaced($order, OrderPlaced::ADMIN));
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

        // create payments
        $this->createOrderPayments($order);

        $this->sendOrderApprovedNotification($order);
    }

    public function sendOrderApprovedNotification(Order $order)
    {
        // send notification to customer
        Notification::route('mail', $order->customer_email)
            ->notify(new OrderApproved($order));
    }

    public function createOrderPayments(Order $order)
    {
        $first_payment_amount = 0;
        $installment_amount = 0;
        $installment_months = 1;
        $first_due_date = Carbon::today();

        foreach($order->orderItems as $elem) {
            if ($elem->payment_plan == PaymentPlan::Once) {
                $first_payment_amount += $elem->unit_price * $elem->quantity;
            } else {
                $first_payment_amount += $elem->down_payment_amount * $elem->quantity;
                $installment_amount += $elem->installment_amount * $elem->quantity;
                $installment_months = $elem->installment_months;
            }
        }

        $order_class_name = get_class($order);
        $order_id = $order->id;

        $first_payment_payload = [
            'payable_type' => $order_class_name,
            'payable_id' => $order_id,
            'amount' => $first_payment_amount,
            'currency' => $this->storeSettingsService->currencyCode(),
            'status_id' => $this->statusService->pending()->id,
            'due_date' => $first_due_date,
        ];
        $this->paymentService->store($first_payment_payload);

        if ($installment_amount > 0) {
            for ($i = 0; $i < $installment_months; $i++) {
                $due_date = $first_due_date->copy()->addMonths($i+1);
                $payment_payload = [
                    'payable_type' => $order_class_name,
                    'payable_id' => $order_id,
                    'amount' => $installment_amount,
                    'currency' => $this->storeSettingsService->currencyCode(),
                    'status_id' => $this->statusService->pending()->id,
                    'due_date' => $due_date,
                ];
                $this->paymentService->store($payment_payload);
            }
        }
    }

    public function getSubTotal(Order $order)
    {
        $order_items = $order->orderItems;
        $value = 0;
        foreach ($order_items as $elem) {
            $value += $elem->unit_price * $elem->quantity;
        }

        return $value;
    }

    public function getOrderValue(Order $order)
    {
        $sub_total = $this->getSubTotal($order);

        return $sub_total;
    }

    public function getOrderInitialPayment(Order $order)
    {
        $order_items = $order->orderItems;
        $value = 0;
        foreach ($order_items as $elem) {
            if ($elem->payment_plan == PaymentPlan::Installment) {
                $value += $elem->down_payment_amount * $elem->quantity;
            } else {
                $value += $elem->unit_price * $elem->quantity;
            }
        }

        return $value;
    }
}
