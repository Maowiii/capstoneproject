<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\Accounts;
use App\Models\EvalYear;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PEDashboardController extends Controller
{
  public function displayPEDashboard()
  {
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();
    $department_id = $user->employee->department_id;
    $first_login = $user->first_login;


    $immediate_superiors = Accounts::where('type', 'IS')->with('employee')->whereHas('employee', function ($query) use ($department_id) {
      $query->where('department_id', $department_id);
    })->get();

    return view('pe-pages.pe_dashboard')
      ->with('IS', $immediate_superiors)
      ->with('first_login', $first_login);
  }

  public function submitPEFirstLogin(Request $request)
  {
    $job_title = $request->job_title;
    $request->session()->put('title', $job_title);
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();

    $user->employee->update([
      'job_title' => $job_title,
    ]);

    $user->update([
      'first_login' => 'false'
    ]);

    return response()->json(['success' => true]);
  }

  public function getNotifications()
  {
    $activeYear = EvalYear::where('status', 'active')->first();
    $schoolYear = $activeYear->sy_start . ' - ' . $activeYear->sy_end;
    $currentDate = Carbon::now();

    $notifications = [];

    if ($activeYear) {
      $kraStart = Carbon::parse($activeYear->kra_start);
      $kraEnd = Carbon::parse($activeYear->kra_end);
      $prStart = Carbon::parse($activeYear->pr_start);
      $prEnd = Carbon::parse($activeYear->pr_end);
      $evalStart = Carbon::parse($activeYear->eval_start);
      $evalEnd = Carbon::parse($activeYear->eval_end);

      $dateEnd = Carbon::parse($activeYear->eval_end);
      $fiveDaysAfterKraEnd = $kraEnd->copy()->addDays(5);
      $fiveDaysAfterStart = $kraStart->copy()->addDays(5);
      $fiveDaysBeforeEnd = $dateEnd->copy()->subDays(5);

      $accountId = session('account_id');
      $pendingAppraisalsCount = Appraisals::where('evaluator_id', $accountId)
        ->whereNull('date_submitted')
        ->whereIn('evaluation_type', ['internal customer 1', 'internal customer 2'])
        ->count();
      $hasPendingSelfEvaluation = Appraisals::where('evaluator_id', $accountId)
        ->whereNull('date_submitted')
        ->where('evaluation_type', 'self evaluation')
        ->exists();

      if ($currentDate <= $fiveDaysAfterStart) {
        $notifications[] = "The evaluation period for school year $schoolYear has started. Check your appraisal page for more information.";
      }
      if ($currentDate >= $fiveDaysAfterKraEnd) {
        $notifications[] = "The KRA encoding has ended. Check your self-evaluation on the appraisal page to view your KRAs.";
      }

      if ($currentDate->between($prStart, $prEnd)) {
        $notifications[] = "Please ensure you have completed your Key Result Areas (KRAs) within the Performance Review period.";
      }

      if ($hasPendingSelfEvaluation) {
        $notifications[] = "Please complete your self-evaluation on the appraisal page.";
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