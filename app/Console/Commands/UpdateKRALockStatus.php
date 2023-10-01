<?php

namespace App\Console\Commands;

use App\Models\Appraisals;
use Illuminate\Console\Command;
use App\Models\EvalYear;
use Carbon\Carbon;

class UpdateKRALockStatus extends Command
{
  protected $signature = 'app:update-k-r-a-lock-status';

  protected $description = 'Update kra lock status based on EvalYear and date condition';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $currentDate = now();

    $evalYear = EvalYear::where('status', 'active')->first();

    if ($evalYear) {
      $targetDate = $evalYear->kra_end;

      if ($currentDate->isSameDay($targetDate) || $currentDate->gt($targetDate)) {
        Appraisals::query()->update(['kra_locked' => true]);

        $this->info('Locked status updated successfully.');
      } else {
        $this->info('No update needed. Current date has not reached the target date.');
      }
    } else {
      $this->info('No active EvalYear found.');
    }
  }
}