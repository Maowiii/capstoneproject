<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\EvalYear;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppraisalsOverviewController extends Controller
{
  public function displayAdminAppraisalsOverview()
  {
    $activeEvalYear = EvalYear::where('status', 'active')->first();

    return view('admin-pages.admin_appraisals_overview', compact('activeEvalYear'));
  }

  public function loadAdminAppraisals()
  {
    $appraisals = Appraisals::with([
      'employee' => function ($query) {
        $query->whereHas('account', function ($subQuery) {
          $subQuery->whereIn('type', ['PE', 'IS', 'CE']);
        });
      }
    ])
      ->get();

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

    return response()->json(['success' => true, 'groupedAppraisals' => $groupedAppraisals]);
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
}