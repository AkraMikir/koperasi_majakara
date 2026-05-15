<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
Schedule::command('dashboard:refresh-cache')->everyFiveMinutes();

// Peringatan jatuh tempo deposito: jalankan setiap hari pukul 07:00
Schedule::command('deposito:generate-peringatan --days=7')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/deposito-peringatan.log'));

// Cek status gadai harian
Schedule::command('gadai:check-status')
    ->dailyAt('00:01')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/gadai-check-status.log'));

