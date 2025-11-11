<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('membership:expire', function () {
    Artisan::call('membership:expire');
})->purpose('Expire user plans whose end_date is in the past')->dailyAt('00:00');
