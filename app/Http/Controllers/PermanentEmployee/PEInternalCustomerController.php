<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\Employees;
use App\Models\Appraisals;
use App\Models\Comments;
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

  public function saveICScores(Request $request)
  {
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
    $newSuggestion = $request->input('newSuggestion');
    $appraisalId = $request->input('appraisalId');

    Comments::updateOrInsert(
      ['appraisal_id' => $appraisalId],
      ['suggestion' => $newSuggestion]
    );
  }

  public function getCommentsAndSuggestions(Request $request)
  {
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
}