<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\FinalScores;
use Illuminate\Support\Facades\Log;
use App\Models\AppraisalAnswers;
use App\Models\Employees;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\Comments;
use App\Models\FormQuestions;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PEInternalCustomerController extends Controller
{
  public function displayICOverview()
  {
    if (session()->has('account_id')) {
      return view('pe-pages.pe_ic_overview');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getICAssign(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    if (Appraisals::tableExists()) {
      $accountId = session('account_id');
      $appraiserId = Employees::where('account_id', $accountId)->value('employee_id');

      $assignments = Appraisals::whereIn('evaluation_type', ['internal customer 1', 'internal customer 2'])
        ->where('evaluator_id', $appraiserId)
        ->with(['employee.department', 'employee'])
        ->with(['evaluator.department'])
        ->paginate(10);

      return response()->json(['success' => true, 'assignments' => $assignments]);
    } else {
      return response()->json(['success' => false]);
    }
  }


  public function getICQuestions()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $ICques = FormQuestions::where('table_initials', 'IC')
      ->where('status', 'active')
      ->get();

    return response()->json(['success' => true, 'ICques' => $ICques]);
  }

  public function showAppraisalForm(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $account_id = session()->get('account_id');
    $employee = Employees::where('account_id', $account_id)->first();
    $accounts = Accounts::where('account_id', $account_id)->first();

    $firstName = null;
    $lastName = null;

    if ($accounts && $accounts->employee) {
      $employee = $accounts->employee;
      $firstName = $employee->first_name;
      $lastName = $employee->last_name;
    }
    return response()->json([
      'success' => true,
      'first_name' => $firstName,
      'last_name' => $lastName,
    ]);
  }

  public function showICForm(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $evaluatorName = $request->input('appraiser_name');
    $evaluatorDepartment = $request->input('appraiser_department');
    $appraiseeName = $request->input('appraisee_name');
    $appraiseeDepartment = $request->input('appraisee_department');

    return view('pe-pages.pe_ic_evaluation', compact('evaluatorName', 'evaluatorDepartment', 'appraiseeName', 'appraiseeDepartment'));
  }

  public function saveICScores(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $questionId = $request->input('questionId');
    $score = $request->input('score');
    $appraisalId = $request->input('appraisalId');

    $appraisalAnswer = AppraisalAnswers::firstOrCreate(
      ['appraisal_id' => $appraisalId, 'question_id' => $questionId],
      ['score' => $score]
    );

    AppraisalAnswers::updateOrInsert(
      ['appraisal_id' => $appraisalId, 'question_id' => $questionId],
      ['score' => $score]
    );

    return response()->json(['success' => true]);
  }

  public function getSavedICScores(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $appraisalId = $request->input('appraisalId');
    $questionId = $request->input('questionId');

    $score = AppraisalAnswers::where([
      'appraisal_id' => $appraisalId,
      'question_id' => $questionId
    ])->value('score');

    return response()->json([
      'success' => true,
      'score' => $score
    ]);
  }

  public function updateService(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $newService = $request->input('newService');
    $appraisalId = $request->input('appraisalId');

    Comments::updateOrInsert(
      ['appraisal_id' => $appraisalId],
      ['customer_service' => $newService]
    );

    return response()->json(['success' => true]);
  }

  public function updateSuggestion(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');    }

    $newSuggestion = $request->input('newSuggestion');
    $appraisalId = $request->input('appraisalId');

    Comments::updateOrInsert(
      ['appraisal_id' => $appraisalId],
      ['suggestion' => $newSuggestion]
    );
  }

  public function getCommentsAndSuggestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');    }

    $appraisalId = $request->input('appraisalId');

    $comments = Comments::where('appraisal_id', $appraisalId)->first();

    if ($comments) {
      $customerService = $comments->customer_service;
      $suggestion = $comments->suggestion;
      return response()->json([
        'success' => true,
        'customerService' => $customerService,
        'suggestion' => $suggestion
      ]);
    } else {
      return response()->json([
        'success' => true,
        'customerService' => null,
        'suggestion' => null
      ]);
    }
  }

  public function loadSignatures(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');    }

    $appraisalId = $request->input('appraisalId');

    $appraisal = Appraisals::find($appraisalId);
    $signature = Signature::where('appraisal_id', $appraisalId)->first();

    $evaluator = $appraisal->evaluator;
    $full_name = $evaluator->first_name . ' ' . $evaluator->last_name;
    $date_submitted = $appraisal->date_submitted;

    $sign_data = null;

    if ($signature) {
      $sign_data = $signature->sign_data;
    }

    return response()->json([
      'success' => true,
      'full_name' => $full_name,
      'date_submitted' => $date_submitted,
      'sign_data' => $sign_data,
    ]);
  }

  public function submitICSignature(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');    }

    $appraisalId = $request->input('appraisalId');
    $esignature = $request->input('esignature');
    $totalWeightedScore = $request->input('totalWeightedScore');

    $employeeId = Appraisals::where('appraisal_id', $appraisalId)->value('employee_id');

    Signature::updateOrCreate(
      ['appraisal_id' => $appraisalId],
      ['sign_data' => $esignature, 'sign_type' => 'IC']
    );

    $appraisal = Appraisals::where('appraisal_id', $appraisalId)->first();

    if (!$appraisal) {
      return response()->json(['success' => false, 'message' => 'Appraisal not found for the given appraisal ID'], 400);
    }

    $appraisal->update([
      'ic_score' => $totalWeightedScore,
      'date_submitted' => now(),
      'locked' => true,
    ]);

    $allFormsSubmitted = Appraisals::where('employee_id', $employeeId)
      ->whereIn('evaluation_type', ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'])
      ->whereNotNull('date_submitted')
      ->count() == 4;

    if ($allFormsSubmitted) {
      $selfEvalScore = Appraisals::where('appraisal_id', $appraisalId)
        ->where('evaluation_type', 'self evaluation')
        ->value('bh_score');

      $ic1Score = Appraisals::where('appraisal_id', $appraisalId)
        ->where('evaluation_type', 'internal customer 1')
        ->value('ic_score');

      $ic2Score = Appraisals::where('appraisal_id', $appraisalId)
        ->where('evaluation_type', 'internal customer 2')
        ->value('ic_score');

      $isEvalScore = Appraisals::where('appraisal_id', $appraisalId)
        ->where('evaluation_type', 'is evaluation')
        ->value('bh_score');

      $behavioralWeight = 0.4;
      $kraWeight = 0.6;

      $behavioralTotalScore = ($selfEvalScore + $ic1Score + $ic2Score + $isEvalScore) / 4;
      $kraSelfEvalScore = Appraisals::where('appraisal_id', $appraisalId)
        ->where('evaluation_type', 'self evaluation')
        ->value('kra_score');

      $kraISEvalScore = Appraisals::where('appraisal_id', $appraisalId)
        ->where('evaluation_type', 'is evaluation')
        ->value('kra_score');

      $kraTotalScore = (($kraSelfEvalScore * 0.4) + ($kraISEvalScore * 0.6)) / 2;

      $finalScore = ($behavioralTotalScore * $behavioralWeight) + ($kraTotalScore * $kraWeight);

      FinalScores::updateOrCreate(
        ['employee_id' => $employeeId],
        ['final_score' => $finalScore]
      );
    } else {
      return response()->json(['success' => true, 'message' => 'IC signature updated and final score computed.']);
    }

    return response()->json(['success' => true, 'message' => 'IC signature updated']);

  }

  public function formChecker(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisalId');
    $appraisal = Appraisals::find($appraisalId);
    $locked = $appraisal->locked;

    return response()->json([
      'form_submitted' => $locked,
    ]);

  }
}