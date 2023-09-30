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
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    if (session()->has('account_id')) {
      $activeEvalYear = EvalYear::where('status', 'active')->first();
      return view('ce-pages.ce_ic_overview', compact('activeEvalYear'));
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function showAppraisalForm(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
    $evaluatorName = $request->input('appraiser_name');
    $evaluatorDepartment = $request->input('appraiser_department');
    $appraiseeName = $request->input('appraisee_name');
    $appraiseeDepartment = $request->input('appraisee_department');

    return view('pe-pages.pe_ic_evaluation', compact('evaluatorName', 'evaluatorDepartment', 'appraiseeName', 'appraiseeDepartment'));
  }

}