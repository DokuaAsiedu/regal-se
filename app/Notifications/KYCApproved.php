<?php

namespace App\Notifications;

use App\Enums\Roles;
use App\Models\KYCSubmission;
use App\Services\KYCService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KYCApproved extends Notification
{
    use Queueable;

    public $kyc;
    public $kycService;
    public $title = 'KYC Approved';
    public $icon = 'document-text';

    /**
     * Create a new notification instance.
     */
    public function __construct(KYCSubmission $kyc)
    {
        $this->kyc = $kyc;
        $this->kycService = app(KYCService::class);
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
        return (new MailMessage)
            ->greeting("Hello $notifiable->name,")
            ->line('We are happy to inform you that your KYC has been approved. You can now access all services available to verified users. Thank you for completing the verification process.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = "Your KYC application has been approved and so you can now access all services available to verified users.";

        return [
            'title' => $this->title,
            'message' => $message,
            'url' => route('client.kyc'),
            'icon' => $this->icon,
            'model_class' => get_class($this->kyc),
            'model_id' => $this->kyc->id,
        ];
    }
}
