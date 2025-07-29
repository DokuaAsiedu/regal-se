<?php

namespace App\Notifications;

use App\Enums\Roles;
use App\Models\KYCSubmission;
use App\Services\KYCService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KYCSubmitted extends Notification
{
    use Queueable;

    public $kyc;
    public $kycService;
    public $recipient_type;
    public $title = 'KYC Submitted';
    public $url;
    public $icon = 'document-text';

    /**
     * Create a new notification instance.
     */
    public function __construct(KYCSubmission $kyc, $recipient_type = Roles::Customer)
    {
        $this->kyc = $kyc;
        $this->kycService = app(KYCService::class);
        $this->recipient_type = $recipient_type;
        $this->url = $this->recipient_type == Roles::Customer ? route('client.kyc') : route('kyc.show', ['kyc' => $this->kyc->id]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->recipient_type == Roles::Admin) {
            $customer_name = $this->kyc->customer_name;
            return (new MailMessage)
                ->greeting("Hello $notifiable->name,")
                ->line("A new KYC has been submitted by $customer_name and is awaiting review.")
                ->action('View Submission', $this->url);
        } else {
            return (new MailMessage)
                ->greeting("Dear $notifiable->name,")
                ->line('We are pleased to inform you that your KYC application has been received and is currently under review. We will get back to you shortly with the next steps.')
                ->action('View Submission', $this->url)
                ->line("Thank you for your patience.");
        }

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $customer_name = $this->kyc->customer_name;
        if ($this->recipient_type == Roles::Admin) {
            $message = "KYC submitted by $customer_name and is awaiting review";
        } else {
            $message = "Your KYC application has been received and is currently under review.";
        }

        return [
            'title' => $this->title,
            'message' => $message,
            'url' => $this->url,
            'icon' => $this->icon,
            'model_class' => get_class($this->kyc),
            'model_id' => $this->kyc->id,
        ];
    }
}
