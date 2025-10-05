<?php

namespace App\Listeners;

use App\Events\KYCApproved;
use App\Notifications\KYCApproved as NotificationsKYCApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendKYCApprovedNotification
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
    public function handle(KYCApproved $event): void
    {
        $event->kyc->user->notify(new NotificationsKYCApproved($event->kyc));
    }
}
