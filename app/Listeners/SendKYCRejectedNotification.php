<?php

namespace App\Listeners;

use App\Events\KYCRejected;
use App\Notifications\KYCRejected as NotificationsKYCRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendKYCRejectedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(KYCRejected $event): void
    {
        $event->kyc->user->notify(new NotificationsKYCRejected($event->kyc));
    }
}
