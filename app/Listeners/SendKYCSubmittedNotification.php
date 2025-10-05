<?php

namespace App\Listeners;

use App\Enums\Roles;
use App\Events\KYCSubmitted;
use App\Notifications\KYCSubmitted as NotificationsKYCSubmitted;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Throwable;

class SendKYCSubmittedNotification
{
    /**
     * Handle the event.
     */
    public function handle(KYCSubmitted $event): void
    {
        $userService = app(UserService::class);

        $event->kyc->user->notify(new NotificationsKYCSubmitted($event->kyc));

        // notify admins
        $admins = $userService->admins()->get();
        Notification::send($admins, new NotificationsKYCSubmitted($event->kyc, Roles::Admin));
    }
}
