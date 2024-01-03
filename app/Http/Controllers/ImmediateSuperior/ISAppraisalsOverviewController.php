<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\EvalYear;
use App\Models\ScoreWeights;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ISAppraisalsOverviewController extends Controller
{
  public function displayISAppraisalsOverview()
  {
    if (session()->has('account_id')) {
      $account_id = session()->get('account_id');
      $user = Employees::where('account_id', $account_id)->first();

      $department_id = $user->department_id;
      $appraisals = Employees::where('department_id', $department_id)->get();

      $activeEvalYear = EvalYear::where('status', 'active')->first();
      $evaluationYears = EvalYear::all();

      return view('is-pages.is_appraisals_overview')
        ->with('appraisals', $appraisals)
        ->with('evaluationYears', $evaluationYears)
        ->with('activeEvalYear', $activeEvalYear);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getData(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    // Handle the rest of your logic here, using $employee
    // $activeYear = EvalYear::where('status', 'active')->first();

    // if ($activeYear) {
    //   $evaluationTypes = ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'];

    //   foreach ($appraisee as $appraiseeItem) {
    //     //Log::info($appraiseeItem);
    //     $existingAppraisals = Appraisals::where('employee_id', $appraiseeItem->employee_id)->count();
    //     //Log::info($existingAppraisals);

    //     // If no existing appraisal records, create new ones
    //     // if ($existingAppraisals == 0) {
    //     //   foreach ($evaluationTypes as $evaluationType) {
    //     //     $evaluatorId = null;

    //     //     if ($evaluationType === 'self evaluation') {
    //     //       $evaluatorId = $appraiseeItem->employee_id;
    //     //     } elseif ($evaluationType === 'is evaluation') {
    //     //       $departmentId = $appraiseeItem->department_id;
    //     //       $isAccount = Accounts::where('type', 'IS')
    //     //         ->whereHas('employee', function ($query) use ($departmentId) {
    //     //           $query->where('department_id', $departmentId);
    //     //         })->first();

    //     //       if ($isAccount) {
    //     //         $evaluatorId = $isAccount->employee->employee_id;
    //     //       }
    //     //     }

    //     //     Appraisals::create([
    //     //       'evaluation_type' => $evaluationType,
    //     //       'employee_id' => $appraiseeItem->employee_id,
    //     //       'evaluator_id' => $evaluatorId,
    //     //       'department_id' => $appraiseeItem->department_id,
    //     //       // Corrected this line
    //     //     ]);
    //     //   }
    //     // }
    //   }
    // }

    $account_id = session()->get('account_id');
    $user = Employees::where('account_id', $account_id)->first();

    $selectedYearDates = null;
    $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;
    $selectedYear = $request->input('selectedYear');
    $search = $request->input('search');

    $sy_start = null;
    $sy_end = null;

    if ($selectedYear) {
      $parts = explode('_', $selectedYear);

      if (count($parts) >= 2) {
        $sy_start = $parts[0];
        $sy_end = $parts[1];
      }

      $selectedYearDates = EvalYear::where('sy_start', $sy_start)->first();
      $table = 'appraisals_' . $selectedYear;

      $department_id = $user->department_id;
      $appraisee = Employees::where('department_id', $department_id)
        ->where('employee_id', '<>', $user->account_id)
        ->whereHas('account', function ($query) {
          $query->where('type', 'PE');
        })
        ->paginate(10);

      $appraisals = Appraisals::from($table)
        ->where('department_id', $user->department_id)
        ->where('employee_id', '<>', $user->account_id)
        ->with('employee', 'evaluator')
        ->paginate(40);

    } elseif ($activeEvalYear) {
      $department_id = $user->department_id;
      $appraisee = Employees::where('department_id', $department_id)
        ->where('employee_id', '<>', $user->account_id)
        ->whereHas('account', function ($query) {
          $query->where('type', 'PE');
        })
        ->paginate(10);

      $appraisals = Appraisals::where('department_id', $user->department_id)
        ->where('employee_id', '<>', $user->account_id)
        ->with('employee', 'evaluator')
        ->paginate(40);
    }

    $appraisalsCollection = $appraisals->getCollection();

    $statuses = $this->calculateStatus($appraisalsCollection);

    $data = [
      'success' => true,
      'appraisee' => $appraisee,
      'appraisals' => $appraisals,
      'is' => $user,
      'status' => $statuses,
    ];

    return response()->json($data);
  }


  public function getEmployees(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    $excludedEmployeeId = $request->input('excludedEmployeeId');

    $accounts = Accounts::whereIn('type', ['PE', 'CE'])->get();

    $employeeIds = $accounts->pluck('account_id');

    $employees1 = Employees::whereIn('account_id', $employeeIds)->get();
    Log::info($employees1);
    $employees = Employees::whereIn('account_id', $employeeIds)->paginate(10);
    Log::info($employees);

    // Retrieve department names
    $departmentIds = $employees->pluck('department_id');
    $departments = Departments::whereIn('department_id', $departmentIds)->pluck('department_name', 'department_id');

    foreach ($employees as $employee) {
      $employee->department_name = $departments[$employee->department_id] ?? 'Unknown Department';
    }

    $evaluatorId = Appraisals::where('employee_id', $excludedEmployeeId)
      ->where('evaluation_type', 'like', 'internal customer%')
      ->whereNotNull('evaluator_id')
      ->pluck('evaluator_id')
      ->first();

    $data = [
      'success' => true,
      'employees' => $employees,
      'evaluatorId' => $evaluatorId
    ];
    return response()->json($data);
  }


  public function updateInternalCustomer(Request $request)
  {
    $appraisalId = $request->input('appraisal_id');
    $evaluatorId = $request->input('evaluator_id');

    // Update the evaluator_id in the Appraisals table
    Appraisals::where('appraisal_id', $appraisalId)
      ->update(['evaluator_id' => $evaluatorId]);

    // Return a response indicating success
    return response()->json(['success' => true]);
  }

  public function assignInternalCustomer(Request $request)
  {
    try {
      // Validate the request data
      $validatedData = $request->validate([
        'employee_id' => 'required|array|between:1,2',
        // Ensure exactly 2 internal customers are selected
        'appraisalId' => 'required|integer',
      ]);

      // Extract validated data
      $employeeIds = $validatedData['employee_id'];
      $appraisalIdIC1 = (int) $validatedData['appraisalId'];
      $appraisalIdIC2 = $appraisalIdIC1 + 1; // Calculate ID for the second internal customer

      // Initialize an array to store updated appraisals
      $updatedAppraisals = [];

      foreach ($employeeIds as $index => $employeeId) {
        $employee = Employees::where('account_id', $employeeId)->first();

        // Try to find an existing appraisal for this employee and appraisal ID
        $existingAppraisal = Appraisals::where('appraisal_id', ($index === 0) ? $appraisalIdIC1 : $appraisalIdIC2)
          ->first();

        if ($existingAppraisal !== null) {
          // Update the existing appraisal record
          $existingAppraisal->evaluator_id = $employeeId;
          $existingAppraisal->save();

          $updatedAppraisals[] = $existingAppraisal;
        } else {
          // Create a new appraisal record
          $appraisal = new Appraisals();
          $appraisal->employee_id = $employeeId;
          $appraisal->evaluator_id = $employeeId;
          $appraisal->department_id = $employee->department_id; // Set the department_id
          $appraisal->appraisal_id = ($index === 0) ? $appraisalIdIC1 : $appraisalIdIC2; // Set the appraisal_id
          $appraisal->evaluation_type = ($index === 0) ? 'internal customer 1' : 'internal customer 2';
          $appraisal->save();

          $updatedAppraisals[] = $appraisal;
        }
      }

      // Return a success response with updated appraisals
      $data = [
        'success' => true,
        'updated_appraisals' => $updatedAppraisals,
      ];
      return response()->json($data);
    } catch (\Exception $e) {
      // Handle any errors, such as database errors or invalid data
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      $data = [
        'error' => 'Failed to assign Internal Customers. ' . $e->getMessage(),
      ];
      return response()->json($data, 400);
    }
  }

  function calculateFinalScores($appraisals)
  {
    $allSubmitted = 0;

    $behavioralCompetenciesRating = 0;
    $behavioralCompetenciesWeightedTotal = 0;

    $kraGrade = 0;
    $kraRating = 0;
    $kraFormsCount = 0;
    $kraWeightedTotal = 0;

    $finalGrade = null;

    $appraisalRatings = [
      'self evaluation' => 0,
      'is evaluation' => 0,
      'internal customer 1' => 0,
      'internal customer 2' => 0,
    ];

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
                $appraisalRatings['self evaluation'] += $bhScore;
                $weightedTotals['self evaluation'] += ($bhScore * $selfEvalWeight);
                Log::info('Self evaluation Computation: (' . $bhScore . ' x ' . $selfEvalWeight . '%) = ' . $weightedTotals['self evaluation']);

                $behavioralCompetenciesRating += $weightedTotals['self evaluation'];

              } elseif ($evaluationType === 'is evaluation') {
                $appraisalRatings['is evaluation'] += $bhScore;
                $weightedTotals['is evaluation'] += ($bhScore * $isWeight);
                Log::info('Immediate superior evaluation Computation: (' . $bhScore . ' x ' . $isWeight . '%) = ' . $weightedTotals['is evaluation']);

                $behavioralCompetenciesRating += $weightedTotals['is evaluation'];

              }
            } elseif ($evaluationType === 'internal customer 1') {
              $appraisalRatings['internal customer 1'] += $icScore;
              $weightedTotals['internal customer 1'] += ($icScore * $ic1Weight);
              Log::info('Internal customer 1 Computation: (' . $icScore . ' x ' . $ic1Weight . '%) = ' . $weightedTotals['internal customer 1']);

              $behavioralCompetenciesRating += $weightedTotals['internal customer 1'];

            } elseif ($evaluationType === 'internal customer 2') {
              $appraisalRatings['internal customer 2'] += $icScore;
              $weightedTotals['internal customer 2'] += ($icScore * $ic2Weight);
              Log::info('Internal customer 2 Computation: (' . $icScore . ' x ' . $ic2Weight . '%) = ' . $weightedTotals['internal customer 2']);

              $behavioralCompetenciesRating += $weightedTotals['internal customer 2'];
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

      $kraRating = $kraGrade / $kraFormsCount;
      $scoreWeights = ScoreWeights::where('eval_id', $evalYearId)->first();

      $bhWeight = $scoreWeights->bh_weight / 100;
      $kraWeight = $scoreWeights->kra_weight / 100;

      $behavioralCompetenciesWeightedTotal = ($behavioralCompetenciesRating * $bhWeight);
      $kraWeightedTotal = ($kraRating * $kraWeight);

      $finalGrade = $behavioralCompetenciesWeightedTotal + $kraWeightedTotal;

      Log::info('Final Grade Calculation:');
      Log::info('Self Eval Weighted Total: ' . $weightedTotals['self evaluation']);
      Log::info('Immediate Superior Weighted Total: ' . $weightedTotals['is evaluation']);
      Log::info('Internal Cust 1 Weighted Total: ' . $weightedTotals['internal customer 1']);
      Log::info('Internal Cust 2 Weighted Total: ' . $weightedTotals['internal customer 2']);

      Log::info('Behavioral Competencies Weighted: ' . $behavioralCompetenciesRating);
      Log::info('Behavioral Competencies Weighted Total: ' . $behavioralCompetenciesWeightedTotal);
      Log::info('KRA Rating: ' . $kraRating);
      Log::info('KRA Weighted Total: ' . $kraWeightedTotal);

      Log::info('Final Grade Computation: (' . $behavioralCompetenciesWeightedTotal . ' x ' . $scoreWeights->bh_weight . '%)  + (' . $kraWeightedTotal . ' x ' . $scoreWeights->kra_weight . '%)');
      Log::info('Final Grade: ' . $finalGrade);
      Log::info('Final Score Calculation Complete');

      $result = [
        'finalGrade' => $finalGrade,
        'behavioralCompetenciesWeightedTotal' => $behavioralCompetenciesWeightedTotal,
        'kraWeightedTotal' => $kraWeightedTotal,
        'weightedTotals' => $weightedTotals,
        'behavioralCompetenciesRating' => $behavioralCompetenciesRating,
        'kraRating' => $kraRating,
        'appraisalRatings' => $appraisalRatings,
        'scoreWeights' => $scoreWeights,
      ];

      return $result;
    } else {
      Log::info('allSubmitted Error Log:' . $allSubmitted);
      Log::error('Final Grade calculation skipped due to date_submitted being null for an appraisal.');

      return null;
    }
  }

  public function calculateStatus(Collection $appraisals)
  {
    // Group appraisals by employee ID
    $groupedAppraisals = $appraisals->groupBy('employee_id');

    // Initialize an empty array to store statuses for each employee
    $statuses = [];

    foreach ($groupedAppraisals as $employeeId => $employeeAppraisals) {
      $status = 'Complete'; // Assume "complete" by default for this employee

      // Check each appraisal for the employee
      foreach ($employeeAppraisals as $appraisal) {
        if ($appraisal->date_submitted === null) {
          // If any appraisal has a null date_submitted, set status to "pending" for this employee
          $status = 'Pending';
          break; // No need to continue checking for this employee
        }
      }

      // Add the status to the array for this employee
      $statuses[$employeeId] = $status;
    }
    return $statuses;
  }

  public function getScoreSummary(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $employeeID = $request->input('employeeID');

    $selectedYearDates = null;
    $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;
    $selectedYear = $request->input('selectedYear');
    $search = $request->input('search');

    $sy_start = null;
    $sy_end = null;

    if ($selectedYear) {
      $parts = explode('_', $selectedYear);

      if (count($parts) >= 2) {
        $sy_start = $parts[0];
        $sy_end = $parts[1];
      }

      $selectedYearDates = EvalYear::where('sy_start', $sy_start)->first();
      $table = 'appraisals_' . $selectedYear;

      $appraisals = Appraisals::from($table)
      ->where('employee_id', $employeeID)
      ->get();
      
    } elseif ($activeEvalYear) {
      // Retrieve appraisals
      $appraisals = Appraisals::where('employee_id', $employeeID)
      ->get();
    }

    $appraiseeFinalScores = $this->calculateFinalScores($appraisals);

    if($appraiseeFinalScores == null){
      return response()->json([
        'success' => false,
      ]);
    }else{
      return response()->json([
        'success' => true,
        'appraiseeFinalScores' => $appraiseeFinalScores,
      ]);
    }
  }
}