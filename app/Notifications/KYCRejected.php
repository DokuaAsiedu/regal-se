<?php

namespace App\Notifications;

use App\Models\KYCSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KYCRejected extends Notification
{
    use Queueable;

    public $kyc;
    public $title = 'KYC Approved';
    public $icon = 'document-text';
    public $rejection_reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(KYCSubmission $kyc)
    {
        $this->kyc = $kyc;
        $this->rejection_reason = $this->kyc->rejection_reason;
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
            ->greeting("Dear $notifiable->name,")
            ->line('We regret to inform you that your KYC submission has been rejected.')
            ->line("**Reason:** {$this->rejection_reason}")
            ->line('Kindly review, correct the issue and resubmit your KYC information')
            ->line('If you need any help, feel free to contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $rejection_reason = $this->kyc->rejection_reason;
        $message = "Unfortunately, your KYC application was rejected for the following reason: $rejection_reason. Kindly review, correct the issue then resubmit your information.";

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
