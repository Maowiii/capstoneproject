<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\Accounts;
use App\Models\EvalYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PEDashboardController extends Controller
{
  public function displayPEDashboard()
  {
    if (session()->has('account_id')) {
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
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function submitPEFirstLogin(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $job_title = $request->job_title;
    $immediate_superior_id = $request->immediate_superior;
    $request->session()->put('title', $job_title);
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();
    Log::debug('Immediate Superior ID: ' . $immediate_superior_id);

    $user->employee->update([
      'job_title' => $job_title,
      'immediate_superior_id' => $immediate_superior_id,
    ]);

    $user->update([
      'first_login' => 'false'
    ]);

    return response()->json(['success' => true]);
  }

  public function getNotifications()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
    $activeYear = EvalYear::where('status', 'active')->first();
    $currentDate = Carbon::now();

    $notifications = [];

    if ($activeYear) {
      $schoolYear = $activeYear->sy_start . ' - ' . $activeYear->sy_end;

      $kraStart = Carbon::parse($activeYear->kra_start);
      $kraEnd = Carbon::parse($activeYear->kra_end);
      $prStart = Carbon::parse($activeYear->pr_start);
      $prEnd = Carbon::parse($activeYear->pr_end);
      $evalStart = Carbon::parse($activeYear->eval_start);
      $evalEnd = Carbon::parse($activeYear->eval_end);

      $dateEnd = Carbon::parse($activeYear->eval_end);
      $fiveDaysAfterKraEnd = $kraEnd->copy()->addDays(5);
      $fiveDaysAfterStart = $kraStart->copy()->addDays(5);
      $fiveDaysAfterPrEnd = $prEnd->copy()->addDays(5);

      $fiveDaysBeforeEvalEnd = $prEnd->copy()->subDays(5);
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

      // KRA Encoding
      if ($currentDate >= $fiveDaysAfterKraEnd) {
        $notifications[] = "The KRA encoding has ended. Check your self-evaluation on the appraisal page to view your KRAs.";
      }

      // Performance Review
      if ($currentDate->between($prStart, $prEnd)) {
        $notifications[] = "Please ensure you have completed your Key Result Areas (KRAs) within the Performance Review period.";
      }
      // KRA NOTIF WALA PA


      if ($currentDate >= $fiveDaysAfterPrEnd) {
        $notifications[] = "The performance review period has ended.";
      }

      // Evaluation Period
      if ($currentDate->between($evalStart, $evalEnd)) {
        if ($currentDate >= $fiveDaysBeforeEvalEnd) {
          $notifications[] = "Please ensure that all your required appraisals are settled before the evaluation period ends.";
        }
      }

      if ($hasPendingSelfEvaluation) {
        $notifications[] = "Please complete your self-evaluation. See appraisals page.";
      }

      if ($pendingAppraisalsCount > 0) {
        $notifications[] = "You have $pendingAppraisalsCount pending internal customer appraisals to complete.";
      }

      $pendingAppraisalsCount = $pendingAppraisalsCount + $hasPendingSelfEvaluation;
      
      // Ending
      if ($currentDate > $dateEnd) {
        if ($pendingAppraisalsCount > 0) {
          $notifications[] = "The evaluation period for school year $schoolYear has ended. You still have $pendingAppraisalsCount appraisals remaining. Please contact the administrator for further assistance.";
        } else {
          $notifications[] = "The evaluation period for school year $schoolYear has ended.";
        }
      }

    } else {
      $notifications[] = "There is no ongoing evaluation.";
    }
    return response()->json(['notifications' => $notifications]);
  }
}