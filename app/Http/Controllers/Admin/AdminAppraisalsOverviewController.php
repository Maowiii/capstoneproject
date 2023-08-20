<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\EvalYear;
use Illuminate\Http\Request;

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

    // Group the appraisals by employee
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

  public function loadSelfEvaluationForm() {
    return view('admin-pages.admin_self_evaluation');
  }

  public function loadISEvaluationForm() {
    return view('admin-pages.admin_is_evaluation');
  }

  public function loadICEvalationForm() {
    return view('admin-pages.admin_ic_evaluation');
  }

}