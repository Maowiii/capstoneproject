<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\ScoreWeights;
use App\Models\AppraisalAnswers;
use App\Models\Employees;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\Comments;
use App\Models\FormQuestions;
use App\Models\Signature;
use App\Models\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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

    return view('pe-pages.pe_ic_evaluation');
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
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

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
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

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
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $appraisalId = $request->input('appraisalId');

    $appraisal = Appraisals::find($appraisalId);
    $signature = Signature::where('appraisal_id', $appraisalId)->first();

    $evaluator = $appraisal->evaluator;
    $full_name = $evaluator->first_name . ' ' . $evaluator->last_name;
    $date_submitted = $appraisal->date_submitted;
    $form_locked = $appraisal->locked;

    $sign_data = null;

    if ($signature) {
      $sign_data = $signature->sign_data;
    }

    return response()->json([
      'success' => true,
      'full_name' => $full_name,
      'date_submitted' => $date_submitted,
      'sign_data' => $sign_data,
      'form_locked' => $form_locked
    ]);
  }

  public function submitICSignature(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $appraisalId = $request->input('appraisalId');
    $esignature = $request->input('esignature');
    $totalWeightedScore = $request->input('totalWeightedScore');

    $employeeId = Appraisals::where('appraisal_id', $appraisalId)->value('employee_id');

    try {
      $maxAllowedSizeBytes = 25 * 1024 * 1024; // 25MB
      $dataSize = strlen($esignature);

      if ($dataSize > $maxAllowedSizeBytes) {
        return response()->json([
          'success' => false,
          'message' => 'Maximum allowed file size is 25MB.'
        ]);
      }

      Signature::updateOrCreate(
        ['appraisal_id' => $appraisalId],
        ['sign_data' => $esignature, 'sign_type' => 'IC']
      );
    } catch (QueryException $e) {
      $errorCode = $e->getCode();
      $errorMessage = $e->getMessage();

      if ($errorCode === '22001') {
        return response()->json([
          'success' => false,
          'message' => 'The uploaded esignature is too large. Please upload a smaller image.'
        ]);
      } elseif ($errorCode === '23000') {
        return response()->json([
          'success' => false,
          'message' => 'Database error: Duplicate entry.'
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Database error: ' . $errorMessage
        ]);
      }
    }

    $appraisal = Appraisals::where('appraisal_id', $appraisalId)->first();

    if (!$appraisal) {
      return response()->json(['success' => false, 'message' => 'Appraisal not found for the given appraisal ID'], 400);
    }

    $date = Carbon::now();

    $appraisal->update([
      'ic_score' => $totalWeightedScore,
      'date_submitted' => $date,
      'locked' => true,
    ]);

    // $allFormsSubmitted = Appraisals::where('employee_id', $employeeId)
    //   ->whereIn('evaluation_type', ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'])
    //   ->whereNotNull('date_submitted')
    //   ->count() == 4;

    $appraisalData = Appraisals::where('employee_id', $employeeId)->get(); // Get all appraisals for the employee
    $allFormsSubmitted = $appraisalData->every(function ($appraisal) {
      return $appraisal->date_submitted !== null;
    });

    if ($allFormsSubmitted) {
      // $selfEvalScore = Appraisals::where('appraisal_id', $appraisalId)
      //   ->where('evaluation_type', 'self evaluation')
      //   ->value('bh_score');

      // $ic1Score = Appraisals::where('appraisal_id', $appraisalId)
      //   ->where('evaluation_type', 'internal customer 1')
      //   ->value('ic_score');

      // $ic2Score = Appraisals::where('appraisal_id', $appraisalId)
      //   ->where('evaluation_type', 'internal customer 2')
      //   ->value('ic_score');

      // $isEvalScore = Appraisals::where('appraisal_id', $appraisalId)
      //   ->where('evaluation_type', 'is evaluation')
      //   ->value('bh_score');

      // $behavioralWeight = 0.4;
      // $kraWeight = 0.6;

      // $behavioralTotalScore = ($selfEvalScore + $ic1Score + $ic2Score + $isEvalScore) / 4;
      // $kraSelfEvalScore = Appraisals::where('appraisal_id', $appraisalId)
      //   ->where('evaluation_type', 'self evaluation')
      //   ->value('kra_score');

      // $kraISEvalScore = Appraisals::where('appraisal_id', $appraisalId)
      //   ->where('evaluation_type', 'is evaluation')
      //   ->value('kra_score');

      // $kraTotalScore = (($kraSelfEvalScore * 0.4) + ($kraISEvalScore * 0.6)) / 2;

      // $finalScore = ($behavioralTotalScore * $behavioralWeight) + ($kraTotalScore * $kraWeight);

      // FinalScores::updateOrCreate(
      //   ['employee_id' => $employeeId],
      //   ['final_score' => $finalScore]
      // );

      $finalScore = $this->calculateFinalScore($appraisalData);
      $roundedFinalScore = round($finalScore[0], 2); // Round to 2 decimal places
      $departmentId = $appraisal->department_id;

      // Log some information for debugging
      Log::info('Trying to update the record.');
      // Log::info('Table Name: ' . (new FinalScores)->getTable());
      Log::info('Employee ID: ' . $employeeId);
      Log::info('Final Score: ' . $finalScore[0]);
      Log::info('rounded Final Score: ' . $roundedFinalScore);

      // Attempt to update the record
      try {
        FinalScores::updateOrCreate(
          [
            'employee_id' => $employeeId,
            'department_id' => $departmentId,
          ],
          ['final_score' => $finalScore[0]]
        );

        Log::info('Record updated successfully.');
      } catch (\Exception $e) {
        Log::error('Error while updating the record: ' . $e->getMessage());
      }
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
    Log::info('appraisal');
    Log::info($appraisal);
    $locked = $appraisal->locked;

    $appraiseeId = $request->input('appraiseeId');
    $accountId = session('account_id');
    Log::info('accountId');
    Log::info($accountId);

    $shouldHideSignatory = ($appraiseeId == $accountId);
    $hasPermission = true;
    $canRequest = true;

    if ($appraisal) {
      ////////////PERMISSION/////////////
      $employeeId = $appraisal->employee_id;
      $evaluatorId = $appraisal->evaluator_id;

      $isAdmin = Accounts::where('account_id', $accountId)->where('type', 'AD')->exists();

      $isImmediateSuperior = Accounts::where('account_id', $accountId)
        ->where('type', 'IS')
        ->whereHas('employee', function ($query) use ($appraisal) {
          $query->where('department_id', $appraisal->department_id);
        })
        ->exists();

      $isEvaluator = ($accountId == $evaluatorId);
      $isEmployee = ($accountId == $employeeId);

      // Check permissions for viewing the form
      if (!($isAdmin || $isEvaluator || $isEmployee || $isImmediateSuperior)) {
        $hasPermission = false;
      }

      if (!$isEvaluator) {
        $canRequest = false;
      }
    }

    // $hasRequest = Requests::where('appraisal_id', $appraisalId)->where('status', 'Pending')->exists();
    $hasRequest = Requests::where('appraisal_id', $appraisalId)
      ->whereIn('status', ['Pending', 'Approved', 'Disapproved'])
      ->latest('created_at') // Get the latest entry
      ->first();

    $response = [
      'form_submitted' => $locked,
      'hideSignatory' => $shouldHideSignatory,
      'hasPermission' => $hasPermission,
      'hasRequest' => $hasRequest !== null,
      'canRequest' => $canRequest,
    ];

    if ($hasRequest) {
      // Get additional information
      $approver = Employees::find($hasRequest->approver_id);

      $timestamp = $hasRequest->updated_at;
      $updated_at = Carbon::parse($timestamp)->format('F j, Y H:i:s');

      // Add information to the response
      $response['status'] = $hasRequest->status;
      $response['feedback'] = $hasRequest->feedback;
      $response['approver_name'] = $approver ? $approver->first_name . ' ' . $approver->last_name : null;
      $response['approved_at'] = $updated_at;
    }

    return response()->json($response);
  }

  function calculateFinalScore($appraisals)
  {
    $behavioralCompetenciesWeightedTotal = 0;
    $kraGrade = 0;
    $kraFinalScore = 0;
    $allSubmitted = 0;
    $kraFormsCount = 0;
    $finalGrade = null;

    $weightedTotals = [
      'self evaluation' => 0,
      'is evaluation' => 0,
      'internal customer 1' => 0,
      'internal customer 2' => 0,
    ];

    Log::info('Starting final score calculation');
    Log::info('Appraisal List:');
    Log::info($appraisals);

    foreach ($appraisals as $appraisal) {
      $evaluationType = $appraisal['evaluation_type'];
      $bhScore = $appraisal['bh_score'];
      $kraScore = $appraisal['kra_score'];
      $icScore = $appraisal['ic_score'];

      Log::info('Processing appraisal for evaluation type: ' . $evaluationType);
      Log::info('Processing appraisal: ' . $appraisal);
      Log::info('BH Score: ' . $bhScore);
      Log::info('KRA Score: ' . $kraScore);
      Log::info('IC Score: ' . $icScore);
      // Log::info('allSubmitted Before Processing:' . $allSubmitted);
      Log::info('allSubmitted kraGrade: ' . $kraGrade);
      Log::info('allSubmitted kraScore: ' . $kraScore);
      Log::info('allSubmitted kraFormsCount: ' . $kraFormsCount);

      if ($appraisal['date_submitted'] !== null) {
        // Retrieve the latest active evaluation year
        $latestActiveEvalYear = EvalYear::where('status', 'active')->latest('eval_id')->first();

        if (!$latestActiveEvalYear) {
          Log::error('Latest active evaluation year not found. Handle this case as needed.');
        } else {
          $evalYearId = $latestActiveEvalYear->eval_id;
          Log::info('Latest active evaluation id founded:' . $evalYearId);

          // Retrieve the score weights for the current evaluation type in the latest active year
          $scoreWeights = ScoreWeights::where('eval_id', $evalYearId)->first();

          if ($scoreWeights) {
            Log::info('Latest active scoreWeights id founded:' . $scoreWeights);

            $selfEvalWeight = $scoreWeights->self_eval_weight / 100;
            $ic1Weight = $scoreWeights->ic1_weight / 100;
            $ic2Weight = $scoreWeights->ic2_weight / 100;
            $isWeight = $scoreWeights->is_weight / 100;

            if ($evaluationType === 'self evaluation' || $evaluationType === 'is evaluation') {
              $kraGrade += $kraScore;
              $kraFormsCount++;
              Log::info('allSubmitted kraGrade: ' . $kraGrade);
              Log::info('allSubmitted kraScore: ' . $kraScore);
              Log::info('allSubmitted kraFormsCount: ' . $kraFormsCount);

              // Update the weighted total based on the evaluation type
              if ($evaluationType === 'self evaluation') {
                $weightedTotals['self evaluation'] += ($bhScore * $selfEvalWeight);
                $behavioralCompetenciesWeightedTotal += $weightedTotals['self evaluation'];

              } elseif ($evaluationType === 'is evaluation') {
                $weightedTotals['is evaluation'] += ($bhScore * $isWeight);
                $behavioralCompetenciesWeightedTotal += $weightedTotals['is evaluation'];

              }
            } elseif ($evaluationType === 'internal customer 1') {
              $weightedTotals['internal customer 1'] += ($icScore * $ic1Weight);
              $behavioralCompetenciesWeightedTotal += $weightedTotals['internal customer 1'];

            } elseif ($evaluationType === 'internal customer 2') {
              $weightedTotals['internal customer 2'] += ($icScore * $ic2Weight);
              $behavioralCompetenciesWeightedTotal += $weightedTotals['internal customer 2'];
            }

          } else {
            Log::error('Final Grade calculation skipped due to scoreWeights being null for an appraisal.');
          }
        }
        $allSubmitted = 1;
      } else {
        Log::error('Final Grade calculation skipped due to date_submitted being null for an appraisal.');

        $allSubmitted = 0;
        $finalGrade = null;
        $kraFormsCount = null;
        break;
      }
    }

    Log::info('allSubmitted After Processing:' . $allSubmitted);

    if ($allSubmitted) {
      Log::info('allSubmitted kraGrade: ' . $kraGrade);
      Log::info('allSubmitted kraScore: ' . $kraScore);
      Log::info('allSubmitted kraFormsCount: ' . $kraFormsCount);

      $kraFinalScore = $kraGrade / $kraFormsCount;
      $scoreWeights = ScoreWeights::where('eval_id', $evalYearId)->first();

      $bhWeight = $scoreWeights->bh_weight / 100;
      $kraWeight = $scoreWeights->kra_weight / 100;

      $finalGrade = ($behavioralCompetenciesWeightedTotal * $bhWeight) + ($kraFinalScore * $kraWeight);

      Log::info('Final Grade Calculation:');
      Log::info('Self Eval Weighted Total: ' . $weightedTotals['self evaluation']);
      Log::info('Immediate Superior Weighted Total: ' . $weightedTotals['is evaluation']);
      Log::info('Internal Cust 1 Weighted Total: ' . $weightedTotals['internal customer 1']);
      Log::info('Internal Cust 2 Weighted Total: ' . $weightedTotals['internal customer 2']);

      Log::info('Behavioral Competencies Weighted Total: ' . $behavioralCompetenciesWeightedTotal);
      Log::info('KRA Weighted Total: ' . $kraFinalScore);
      Log::info('Final Grade Computation: (' . $behavioralCompetenciesWeightedTotal . ' x ' . $scoreWeights->bh_weight . '%)  + (' . $kraFinalScore . ' x ' . $scoreWeights->kra_weight . '%)');
      Log::info('Final Grade: ' . $finalGrade);
    } else {
      Log::info('allSubmitted Error Log:' . $allSubmitted);

      Log::error('Final Grade calculation skipped due to date_submitted being null for an appraisal.');
    }

    Log::info('Final Score Calculation Complete');

    return [$finalGrade, $behavioralCompetenciesWeightedTotal, $kraFinalScore, $weightedTotals];
  }
}