<?php

namespace App\Http\Controllers\ContractualEmployee;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\Appraisals;
use App\Models\EvalYear;
use Illuminate\Http\Request;

class CEInternalCustomerController extends Controller
{
  public function displayCEICOverview()
  {
    $activeEvalYear = EvalYear::where('status', 'active')->first();

    return view('ce-pages.ce_ic_overview', compact('activeEvalYear'));
  }

  public function showAppraisalForm(Request $request)
  {
    $evaluatorName = $request->input('appraiser_name');
    $evaluatorDepartment = $request->input('appraiser_department');
    $appraiseeName = $request->input('appraisee_name');
    $appraiseeDepartment = $request->input('appraisee_department');

    return view('pe-pages.pe_ic_evaluation', compact('evaluatorName', 'evaluatorDepartment', 'appraiseeName', 'appraiseeDepartment'));
  }

}
