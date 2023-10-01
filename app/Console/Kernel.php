<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  protected function schedule(Schedule $schedule): void
  {
    $schedule->command('app:update-eval-lock-status')->dailyAt('00:00');
    $schedule->command('app:update-k-r-a-lock-status')->dailyAt('00:00');
    $schedule->command('update:locked-status')->dailyAt('00:00');
    $schedule->command('app:update-p-r-lock-status')->dailyAt('00:00');
  }

  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }

  protected $middlewareGroups = [
    'web' => [
      \App\Http\Middleware\SessionExpired::class,
    ],
  ];
}