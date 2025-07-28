<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApproved extends Notification
{
    use Queueable;

    public $order;
    public $orderService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->orderService = app(OrderService::class);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Order Confirmation')
            ->markdown('mail.order-approved', [
                'order' => $this->order,
                'order_total' => $this->orderService->getOrderValue($this->order),
                'sub_total' => $this->orderService->getSubTotal($this->order),
                'initial_payment' => $this->orderService->getOrderInitialPayment($this->order),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
