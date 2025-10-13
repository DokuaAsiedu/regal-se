<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:handle-due-payments')
    ->dailyAt('10:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->sendOutputTo(storage_path('logs/auto_charge.log'));

