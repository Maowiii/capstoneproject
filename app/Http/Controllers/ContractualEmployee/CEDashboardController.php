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
    if (session()->has('account_id')) {
      $account_id = session()->get('account_id');
      $user = Accounts::where('account_id', $account_id)->with('employee')->first();
      $department_id = $user->employee->department_id;
      $first_login = $user->first_login;

      $immediate_superiors = Accounts::where('type', 'IS')->with('employee')->whereHas('employee', function ($query) use ($department_id) {
        $query->where('department_id', $department_id);
      })->get();
      return view('ce-pages.ce_dashboard')->with('IS', $immediate_superiors)->with('first_login', $first_login);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getNotifications()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

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

  public function getRemainingAppraisals()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $accountId = session('account_id');
    $activeYear = EvalYear::where('status', 'active')->first();

    if ($activeYear) {
      $pendingAppraisalsCount = Appraisals::where('evaluator_id', $accountId)
        ->whereNull('date_submitted')
        ->count();

      $completedAppraisalsCount = Appraisals::where('evaluator_id', $accountId)
        ->whereNotNull('date_submitted') // Changed this condition
        ->count();

      $totalAppraisalsCount = Appraisals::where('evaluator_id', $accountId)->count();

      return response()->json([
        'success' => true,
        'pendingAppraisalsCount' => $pendingAppraisalsCount,
        'completedAppraisalsCount' => $completedAppraisalsCount,
        // Matched variable name
        'totalAppraisalsCount' => $totalAppraisalsCount,
      ]);
    } else {
      return response()->json(['success' => false]);
    }
  }

  public function submitFirstLogin(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
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

}