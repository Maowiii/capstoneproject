<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\EvalYear;
use App\Models\KRA;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ISDashboardController extends Controller
{
  public function displayISDashboard()
  {
    if (session()->has('account_id')) {
      $account_id = session()->get('account_id');
      $user = Accounts::where('account_id', $account_id)->with('employee')->first();
      $first_login = $user->first_login;
      return view('is-pages.is_dashboard', ['first_login' => $first_login]);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function submitISPosition(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $position = $request->position;
    $request->session()->put('title', $position);
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();

    $user->employee->update([
      'position' => $position,
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
    
    $immediateSuperior = session()->get('account_id');
    $activeYear = EvalYear::where('status', 'active')->first();
    $currentDate = Carbon::now();

    $notifications = [];
    $data = [];

    if ($activeYear) {
      $schoolYear = $activeYear->sy_start . ' - ' . $activeYear->sy_end;

      $kraStart = Carbon::parse($activeYear->kra_start);
      $kraEnd = Carbon::parse($activeYear->kra_end);
      $prStart = Carbon::parse($activeYear->pr_start);
      $prEnd = Carbon::parse($activeYear->pr_end);
      $evalStart = Carbon::parse($activeYear->eval_start);
      $evalEnd = Carbon::parse($activeYear->eval_end);

      $dateEnd = Carbon::parse($activeYear->eval_end);
      $fiveDaysAfterKraStart = $kraStart->copy()->addDays(5);
      $fiveDaysAfterKraEnd = $kraEnd->copy()->addDays(5);
      $fiveDaysAfterStart = $kraStart->copy()->addDays(5);
      $fiveDaysAfterPrEnd = $prEnd->copy()->addDays(5);

      $fiveDaysBeforeEvalEnd = $prEnd->copy()->subDays(5);
      $fiveDaysBeforeEnd = $dateEnd->copy()->subDays(5);

      $accountId = session('account_id');

      $pendingAppraisalCount = Appraisals::where('evaluator_id', $accountId)
        ->whereNull('date_submitted')
        ->whereIn('evaluation_type', ['is evaluation'])
        ->count();
      $completedAppraisalCount = Appraisals::where('evaluator_id', $accountId)
        ->whereNotNull('date_submitted')
        ->whereIn('evaluation_type', ['is evaluation'])
        ->count();
      $totalAppraisalCount = Appraisals::where('evaluator_id', $accountId)
        ->whereIn('evaluation_type', ['is evaluation'])
        ->count();

      $immediateSuperiorAccountId = session()->get('account_id');

      $immediateSuperiorId = Employees::whereHas('account', function ($query) use ($immediateSuperiorAccountId) {
        $query->where('account_id', $immediateSuperiorAccountId);
      })->value('employee_id');
      

      $immediateSuperiorDepartmentId = Employees::where('employee_id', $immediateSuperiorId)->value('department_id');
      $departmentAppraisals = Appraisals::whereHas('employee', function ($query) use ($immediateSuperiorDepartmentId) {
        $query->where('department_id', $immediateSuperiorDepartmentId)->whereIn('evaluation_type', ['is evaluation']);
      })->get();

      $departmentAppraisalsIC = Appraisals::whereHas('employee', function ($query) use ($immediateSuperiorDepartmentId) {
        $query->where('department_id', $immediateSuperiorDepartmentId)->whereIn('evaluation_type', ['internal customer 1', 'internal customer 2']);
      })->get();

      $completedKRACount = 0;
      $assignedICCount = 0;
      $totalICCount = 0;

      foreach ($departmentAppraisalsIC as $appraisal) {
        $appraisalID = $appraisal->appraisal_id;
        $ICCount = Appraisals::where('appraisal_id', $appraisalID)
          ->whereIn('evaluation_type', ['internal customer 1', 'internal customer 2'])
          ->count();

        if ($ICCount > 0) {
          $totalICCount++;

        }
      }

      foreach ($departmentAppraisalsIC as $appraisal) {
        $ICCount = Appraisals::where('appraisal_id', $appraisal->appraisal_id)
          ->whereIn('evaluation_type', ['internal customer 1', 'internal customer 2'])
          ->whereNotNull('evaluator_id')
          ->count();

        if ($ICCount > 0) {
          $assignedICCount++;
        }
      }

      foreach ($departmentAppraisals as $appraisal) {
        $kraCount = KRA::where('appraisal_id', $appraisal->appraisal_id)
          ->whereNotNull('kra')
          ->whereNotNull('kra_weight')
          ->whereNotNull('objective')
          ->count();
        if ($kraCount > 0) {
          $completedKRACount++;
        }
      }

      $data['encodedKRACount'] = $completedKRACount;
      $data['completedAppraisalCount'] = $completedAppraisalCount;
      $data['totalAppraisalCount'] = $totalAppraisalCount;
      $data['assignedICCount'] = $assignedICCount;
      $data['totalICCount'] = $totalICCount;

      if ($currentDate <= $fiveDaysAfterStart) {
        $notifications[] = "The evaluation period for school year $schoolYear has started. Check your appraisal page for more information.";
      }

      // KRA Encoding
      if ($currentDate <= $fiveDaysAfterKraStart) {
        $notifications[] = "The KRA encoding has started. Please insert KRAs for your employees.";
      }
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

      if ($pendingAppraisalCount > 0) {
        $notifications[] = "You have $pendingAppraisalCount pending appraisals to complete.";
      }

      // Ending
      if ($currentDate > $dateEnd) {
        if ($pendingAppraisalCount > 0) {
          $notifications[] = "The evaluation period for school year $schoolYear has ended. You still have $pendingAppraisalCount appraisals remaining. Please contact the administrator for further assistance.";
        } else {
          $notifications[] = "The evaluation period for school year $schoolYear has ended.";
        }
      }
    } else {
      $notifications[] = "There is no ongoing evaluation.";
    }

    return response()->json(['notifications' => $notifications, 'data' => $data]);
  }
}