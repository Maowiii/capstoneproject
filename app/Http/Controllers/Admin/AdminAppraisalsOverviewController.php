<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\EvalYear;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminAppraisalsOverviewController extends Controller
{
  public function displayAdminAppraisalsOverview()
  {
    $evaluationYears = EvalYear::all();
    $activeEvalYear = EvalYear::where('status', 'active')->first();

    return view('admin-pages.admin_appraisals_overview', compact('evaluationYears', 'activeEvalYear'));
  }

  public function loadAdminAppraisals(Request $request)
  {
    $selectedYear = $request->input('selectedYear');

    $sy_start = null;
    $sy_end = null;
    $selectedYearDates = null;

    if ($selectedYear) {
      Log::debug('Selected Year Condition');
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
      $appraisalsModel = new Appraisals;
      $appraisalsModel->setTable($table);
      $tableName = $appraisalsModel->getTable();

      Log::debug('Table Name: ' . $tableName);

      $appraisals = $appraisalsModel->with([
        'employee' => function ($query) {
          $query->whereHas('account', function ($subQuery) {
            $subQuery->whereIn('type', ['PE', 'IS', 'CE']);
          });
        }
      ])->get();

      Log::debug($appraisals);

      $appraisalsModel->setTable(null);

    } else { // Active Year
      Log::debug('Active Year Condition');
      $selectedYearDates = EvalYear::where('status', 'active')->first();

      $appraisals = Appraisals::with([
        'employee' => function ($query) {
          $query->whereHas('account', function ($subQuery) {
            $subQuery->whereIn('type', ['PE', 'IS', 'CE']);
          });
        }
      ])->get();

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
    return view('admin-pages.admin_self_evaluation');
  }

  public function loadISEvaluationForm()
  {
    return view('admin-pages.admin_is_evaluation');
  }

  public function loadICEvaluationForm()
  {
    return view('admin-pages.admin_ic_evaluation');
  }

  public function loadSignatureOverview(Request $request)
  {
    $employeeID = $request->input('employeeID');

    $appraisals = Appraisals::where('employee_id', $employeeID)
      ->with(['employee', 'signatures', 'evaluator'])
      ->get();

    return response()->json(['success' => true, 'appraisals' => $appraisals]);
  }

  public function loadSignature(Request $request)
  {
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