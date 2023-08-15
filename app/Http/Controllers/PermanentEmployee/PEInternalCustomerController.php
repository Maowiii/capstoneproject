<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\Appraisals;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PEInternalCustomerController extends Controller
{
  public function displayICOverview()
  {
    return view('pe-pages.pe_ic_overview');
  }

  public function getICAssign()
  {
    $accountId = session('account_id');

    // Get the appraiser's employee ID
    $appraiserId = Employees::where('account_id', $accountId)->value('employee_id');

    $assignments = Appraisals::where('evaluation_type', 'internal customer')
      ->where('evaluator_id', $appraiserId)
      ->with(['employee.department', 'employee']) // Load employee and its department
      ->with(['evaluator.department'])
      ->get();

    return response()->json($assignments);
  }

  public function getICQuestions()
  {
    $ICques = FormQuestions::where('table_initials', 'IC')
      ->where('status', 'active')
      ->get();

    return response()->json(['success' => true, 'ICques' => $ICques]);
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