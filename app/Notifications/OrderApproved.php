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
    public $payment_link;
    public $orderService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $payment_link)
    {
        $this->order = $order;
        $this->orderService = app(OrderService::class);
        $this->payment_link = $payment_link;
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
        $initial_payment = $this->orderService->getInitialPayment($this->order);
        $amount = $initial_payment->amount;
        $currency = $initial_payment->currency;
        $formatted_amount = formatCurrency($amount, $currency);
        $action_text = "Pay $formatted_amount";

        return (new MailMessage)
            ->markdown('mail.order-approved', [
                'order' => $this->order,
                'order_total' => $this->orderService->getOrderValue($this->order),
                'sub_total' => $this->orderService->getSubTotal($this->order),
                'initial_payment_amount' => $amount,
                'formatted_initial_payment_amount' => $formatted_amount,
                'currency' => $currency,
                'action_text' => $action_text,
                'payment_link' => $this->payment_link,
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
