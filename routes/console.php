<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Запуск каждый день в полночь обновления данных в бд
app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('fetch:appdata')->dailyAt('00:00');
});

