<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\AdminAppraisals;
use App\Models\EvalYear;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminAppraisalsOverviewController extends Controller
{
  public function displayAdminAppraisalsOverview()
  {
    if (session()->has('account_id')) {
      $evaluationYears = EvalYear::all();
      $activeEvalYear = EvalYear::where('status', 'active')->first();

      return view('admin-pages.admin_appraisals_overview', compact('evaluationYears', 'activeEvalYear'));
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadAdminAppraisals(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $selectedYear = $request->input('selectedYear');
    $search = $request->input('search');

    Log::debug('Search Query: ' . $search);

    $sy_start = null;
    $sy_end = null;
    $selectedYearDates = null;

    if ($selectedYear) { // Selected Year
      $parts = explode('_', $selectedYear);

      if (count($parts) >= 2) {
        $sy_start = $parts[0];
        $sy_end = $parts[1];
      }

      $selectedYearDates = EvalYear::where('sy_start', $sy_start)->first();

      if (!$selectedYearDates) {
        return response()->json(['success' => false, 'error' => 'Selected year not found.']);
      }

      $table = 'appraisals_' . $selectedYear;
      $appraisalsModel = new AdminAppraisals;
      $appraisalsModel->setTable($table);

      // TEST
      if ($search) {
        // Log::debug('Search Query: ' . $search);

        $appraisals = $appraisalsModel
          ->join('employees', 'appraisals.employee_id', '=', 'employees.employee_id')
          ->where(function ($query) use ($search) {
            $query->where('employees.employee_number', 'like', '%' . $search . '%')
              ->orWhere('employees.first_name', 'like', '%' . $search . '%')
              ->orWhere('employees.last_name', 'like', '%' . $search . '%');
          })
          ->get();
        $appraisalsModel->setTable(null);
        // Log::debug('Appraisal:' . $appraisals);

      } else {
        $appraisals = $appraisalsModel->get();
        $appraisalsModel->setTable(null);
      }
      /*

      $table = 'appraisals_' . $selectedYear;
      $appraisalsModel = new AdminAppraisals;
      $appraisalsModel->setTable($table);

      $appraisals = $appraisalsModel->get();

      $appraisalsModel->setTable(null);
      */
    } else { // Active Year
      $selectedYearDates = EvalYear::where('status', 'active')->first();
      if ($search) {
        $appraisals = Appraisals::with('employee')
          ->join('employees', 'employees.employee_id', '=', 'appraisals.employee_id')
          ->where(function ($query) use ($search) {
            $query->where('employees.employee_number', 'like', '%' . $search . '%')
              ->orWhere('employees.first_name', 'like', '%' . $search . '%')
              ->orWhere('employees.last_name', 'like', '%' . $search . '%');
          })
          ->get();

      } else {
        $appraisals = Appraisals::with([
          'employee' => function ($query) {
            $query->whereHas('account', function ($subQuery) {
              $subQuery->whereIn('type', ['PE', 'IS', 'CE']);
            });
          }
        ])->get();
      }

      if (!$selectedYearDates) {
        return response()->json(['success' => false, 'error' => 'Selected year not found.']);
      }
    }

    $groupedAppraisals = [];
    foreach ($appraisals as $appraisal) {
      $employeeId = $appraisal->employee->employee_id;
      if (!isset($groupedAppraisals[$employeeId])) {
        $groupedAppraisals[$employeeId] = [
          'employee' => $appraisal->employee,
          'appraisals' => [],
        ];
      }
      $groupedAppraisals[$employeeId]['appraisals'][] = $appraisal;
    }

    return response()->json(['success' => true, 'groupedAppraisals' => $groupedAppraisals, 'selectedYearDates' => $selectedYearDates]);
  }

  public function loadSelfEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_self_evaluation');
  }

  public function loadISEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_is_evaluation');
  }

  public function loadICEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_ic_evaluation');
  }

  public function loadSignatureOverview(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    $employeeID = $request->input('employeeID');

    $appraisals = Appraisals::where('employee_id', $employeeID)
      ->with(['employee', 'signatures', 'evaluator'])
      ->get();

    return response()->json(['success' => true, 'appraisals' => $appraisals]);
  }

  public function loadSignature(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    $appraisalID = $request->input('appraisalID');

    $appraisal = Appraisals::find($appraisalID);
    $signature = Signature::where('appraisal_id', $appraisalID)->first();

    $sign_data = null;

    if ($signature) {
      $sign_data = $signature->sign_data;
    }

    return response()->json([
      'success' => true,
      'sign_data' => $sign_data,
    ]);
  }

  public function lockUnlockAppraisal(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
    $appraisalID = $request->input('appraisalID');
    $appraisal = Appraisals::find($appraisalID);

    if ($appraisal) {
      $locked = $appraisal->locked;

      $appraisal->update(['locked' => !$locked]);

      return response()->json(['success' => true, 'locked' => !$locked]);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
    }
  }

}