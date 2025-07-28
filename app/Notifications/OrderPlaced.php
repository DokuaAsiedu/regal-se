<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OrderPlaced extends Notification
{
    use Queueable;

    const CUSTOMER = 'CUSTOMER';
    const ADMIN = 'ADMIN';

    public $order;
    public $orderService;
    public $recipient_type;
    public $title = 'Order Placed';
    public $url;
    public $icon = 'shopping-bag';

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $recipient_type = self::CUSTOMER)
    {
        $this->order = $order;
        $this->orderService = app(OrderService::class);
        $this->recipient_type = $recipient_type;
        $this->url = $this->recipient_type == self::ADMIN ? route('orders.show', ['order' => $this->order->id]) : '';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable instanceof AnonymousNotifiable ? ['mail'] : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->recipient_type == self::ADMIN) {
            return (new MailMessage)
                ->greeting("Hello $notifiable->name,")
                ->line(new HtmlString("A new order <b>#{$this->order->code}</b> has been placed by {$this->order->customer_name}"))
                ->action('View Order', route('orders.show', ['order' => $this->order->id]));
        }

        return (new MailMessage)
            ->markdown('mail.order-placed-by-customer', [
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
        $customer_name = $this->order->customer_name;
        $order_code = $this->order->code;
        if ($this->recipient_type == self::ADMIN) {
            $message = "A new order #$order_code has been placed by $customer_name and is waiting approval";
        } else {
            $message = "You have placed a new order #$order_code";
        }

        return [
            'title' => $this->title,
            'message' => $message,
            'url' => $this->url,
            'icon' => $this->icon,
            'model_class' => get_class($this->order),
            'model_id' => $this->order->id,
        ];
    }
}
