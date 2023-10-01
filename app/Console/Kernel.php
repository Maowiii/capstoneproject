<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * Define the application's command schedule.
   */
  protected function schedule(Schedule $schedule): void
  {
    $schedule->command('update:locked-status')
      ->dailyAt('00:00'); // Schedule at 12 AM 
  }

  /**
   * Register the commands for the application.
   */
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