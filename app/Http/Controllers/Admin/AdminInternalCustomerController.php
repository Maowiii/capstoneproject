<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\Appraisals;
use App\Models\Comments;
use App\Models\Employees;
use App\Models\EvalYear;
use App\Models\FormQuestions;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminInternalCustomerController extends Controller
{
  public function loadICEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_ic_evaluation');
  }

  public function getICQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $sy = $request->input('sy');

    if ($sy !== 'null') {
      $table = 'form_questions_' . $sy;
      $formQuestionsModel = new FormQuestions;
      $formQuestionsModel->setTable($table);
      $ICques = $formQuestionsModel->where('table_initials', 'IC')
        ->where('status', 'active')
        ->get();
    } else {
      $ICques = FormQuestions::where('table_initials', 'IC')
        ->where('status', 'active')
        ->get();
    }

    return response()->json(['success' => true, 'ICques' => $ICques]);
  }

  public function getSavedICScores(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $sy = $request->input('sy');
    $appraisalId = $request->input('appraisalId');
    $questionId = $request->input('questionId');

    if ($sy !== 'null') {
      $table = 'appraisal_answers_' . $sy;
      $appraisalAnswersModel = new AppraisalAnswers;
      $appraisalAnswersModel->setTable($table);
      $score = $appraisalAnswersModel->where([
        'appraisal_id' => $appraisalId,
        'question_id' => $questionId
      ])->value('score');
    } else {
      $score = AppraisalAnswers::where([
        'appraisal_id' => $appraisalId,
        'question_id' => $questionId
      ])->value('score');
    }

    return response()->json([
      'success' => true,
      'score' => $score
    ]);
  }

  public function getCommentsAndSuggestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    Log::debug('COMMENTS');

    $sy = $request->input('sy');
    $appraisalId = $request->input('appraisalId');

    if ($sy !== 'null') {
      $table = 'comments_' . $sy;
      $commentsModel = new Comments;
      $commentsModel->setTable($table);
      $comments = $commentsModel->where('appraisal_id', $appraisalId)->first();
    } else {
      $comments = Comments::where('appraisal_id', $appraisalId)->first();
    }

    if ($comments) {
      Log::debug('May Laman');
      $customerService = $comments->customer_service;
      $suggestion = $comments->suggestion;
      return response()->json([
        'success' => true,
        'customerService' => $customerService,
        'suggestion' => $suggestion
      ]);
    } else {
      Log::debug('Walang Laman.');
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
      return view('auth.login');
    }

    $sy = $request->input('sy');
    $appraisalId = $request->input('appraisalId');

    if ($sy !== 'null') {
      $appraisalTable = 'appraisals_' . $sy;
      $signatureTable = 'signature_' . $sy;

      $appraisalsModel = new Appraisals;
      $appraisalsModel->setTable($appraisalTable);
      $appraisal = $appraisalsModel->find($appraisalId);

      $signatureModel = new Signature;
      $signatureModel->setTable($signatureTable);
      $signature = $signatureModel->where('appraisal_id', $appraisalId)->first();
    } else {
      $appraisal = Appraisals::find($appraisalId);
      $signature = Signature::where('appraisal_id', $appraisalId)->first();
    }

    $evaluator = $appraisal ? $appraisal->evaluator : null;
    $full_name = $evaluator ? $evaluator->first_name . ' ' . $evaluator->last_name : null;
    $date_submitted = $appraisal ? $appraisal->date_submitted : null;

    $sign_data = $signature ? $signature->sign_data : null;

    return response()->json([
      'success' => true,
      'full_name' => $full_name,
      'date_submitted' => $date_submitted,
      'sign_data' => $sign_data,
    ]);
  }

}