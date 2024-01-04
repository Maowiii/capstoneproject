<?php

namespace App\Console\Commands;

use App\Models\Requests;
use App\Models\Appraisals;
use Carbon\Carbon;
use App\Models\EvalYear;

use Illuminate\Console\Command;

class UpdateCustomDeadline extends Command
{
  protected $signature = 'app:update-custom-deadline';

  protected $description = 'Update lock status based on custom deadline_type';

  public function handle()
  {
    $currentDate = now();

    $evalYear = EvalYear::where('status', 'active')->first();

    if ($evalYear) {
      $approvedRequests = Requests::where('status', 'Approved')->get();

      foreach ($approvedRequests as $request) {
        $deadlineDate = Carbon::parse($request->deadline);

        if ($currentDate->isSameDay($deadlineDate)) {
          $deadlineTypes = explode('_', $request->deadline_type);

          foreach ($deadlineTypes as $phase) {
            $appraisal = Appraisals::find($request->appraisal_id);

            if ($appraisal) {
              if (in_array($appraisal->evaluation_type, ["internal customer 1", "internal customer 2"])) {
                $appraisal->update(['eval_locked' => true]);
              } else {
                $columnName = $phase . '_locked';
                $appraisal->update([$columnName => true]);
              }
            }
          }
        }
      }

      $this->info('Locked status updated successfully.');
    } else {
      $this->info('No active EvalYear found.');
    }
  }
}