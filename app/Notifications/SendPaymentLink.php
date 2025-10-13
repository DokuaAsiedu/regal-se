<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPaymentLink extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    public $payment_link;
    public $payable_code;
    public $recipient;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, $payment_link)
    {
        $this->payment = $payment;
        $this->payment_link = $payment_link;
        $this->payable_code = '# ' . $this->payment->payable->code;
        $this->recipient = $this->payment->payable->customer_email;
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
        $amount = formatCurrency($this->payment->amount, $this->payment->currency);
        return (new MailMessage)
            ->subject("Payment for $this->payable_code")
            ->line("Please use the button below to make payment for your order.")
            ->action("Pay $amount", $this->payment_link)
            ->line('Please ignore this email if you have already completed this payment.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'title' => "Payment for $this->payable_code",
    //         'message' => "Please use this link to make payment. Ignore this message, if you've already made payment",
    //         'url' => $this->payment_link,
    //         'icon' => 'banknotes',
    //         'model_class' => $this->payment->payable_type,
    //         'model_id' => $this->payment->payable_id,
    //     ];
    // }
}
