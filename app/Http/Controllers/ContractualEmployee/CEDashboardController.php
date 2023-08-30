<?php

namespace App\Http\Controllers\ContractualEmployee;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\EvalYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CEDashboardController extends Controller
{
  public function displayCEDashboard()
  {
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();
    $department_id = $user->employee->department_id;

    $immediate_superiors = Accounts::where('type', 'IS')->with('employee')->whereHas('employee', function ($query) use ($department_id) {
      $query->where('department_id', $department_id);
    })->get();

    return view('ce-pages.ce_dashboard')->with('IS', $immediate_superiors);
  }

  public function getNotifications()
  {
    $activeYear = EvalYear::where('status', 'active')->first();
    $schoolYear = $activeYear->sy_start . ' - ' . $activeYear->sy_end;
    $currentDate = Carbon::now();

    $notifications = [];

    if ($activeYear) {
      $dateStart = Carbon::parse($activeYear->kra_start);
      $dateEnd = Carbon::parse($activeYear->eval_end);
      $fiveDaysAfterStart = $dateStart->copy()->addDays(5);
      $fiveDaysBeforeEnd = $dateEnd->copy()->subDays(5);

      $accountId = session('account_id');
      $pendingAppraisalsCount = Appraisals::where('evaluator_id', $accountId)
        ->whereNull('date_submitted')
        ->count();

      if ($currentDate <= $fiveDaysAfterStart) {
        $notifications[] = "The evaluation period for school year $schoolYear has started. Check your appraisal page for more information.";
      } 
      if ($pendingAppraisalsCount > 0) {
        $notifications[] = "You have $pendingAppraisalsCount pending appraisals to complete.";
      } 
      if ($currentDate >= $fiveDaysBeforeEnd) {
        $notifications[] = "Please ensure that all your required appraisals are settled before the evaluation period ends.";

        if ($currentDate > $dateEnd) {
          $notifications[] = "The evaluation period has ended. You still have $pendingAppraisalsCount appraisals remaining. Please contact the administrator for further assistance.";
        }
      }
    }
    return response()->json(['notifications' => $notifications]);
  }
}