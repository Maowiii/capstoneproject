<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DepartmentalAnalyticsController extends Controller
{
  public function displayDepartmentalAnalytics()
  {
    if (session()->has('account_id')) {

      return view('admin-pages.admin_departmental_analytics');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadBCQuestions(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $departmentID = $request->input('departmentID');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $formQuestionsTable = 'form_questions_' . $selectedYear;
      $answersTable = 'appraisal_answers_' . $selectedYear;

      $sidQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($sidQuestions as $sidQuestion) {
        $averageScores = AppraisalAnswers::from($answersTable)
          ->join('appraisals_' . $selectedYear, function ($join) use ($departmentID, $selectedYear) {
            $join->on('appraisal_answers_' . $selectedYear . '.appraisal_id', '=', 'appraisals_' . $selectedYear . '.appraisal_id')
              ->where('appraisals_' . $selectedYear . '.department_id', $departmentID);
          })
          ->where('question_id', $sidQuestion->question_id)
          ->avg('score');

        $sidQuestion->average_score = number_format($averageScores, 2);
      }

      $srQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($srQuestions as $srQuestion) {
        $averageScores = AppraisalAnswers::from($answersTable)
          ->join('appraisals_' . $selectedYear, function ($join) use ($departmentID, $selectedYear) {
            $join->on('appraisal_answers_' . $selectedYear . '.appraisal_id', '=', 'appraisals_' . $selectedYear . '.appraisal_id')
              ->where('appraisals_' . $selectedYear . '.department_id', $departmentID);
          })
          ->where('question_id', $srQuestion->question_id)
          ->avg('score');

        $srQuestion->average_score = number_format($averageScores, 2);
      }

      $sQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($sQuestions as $sQuestion) {
        $averageScores = AppraisalAnswers::from($answersTable)
          ->join('appraisals_' . $selectedYear, function ($join) use ($departmentID, $selectedYear) {
            $join->on('appraisal_answers_' . $selectedYear . '.appraisal_id', '=', 'appraisals_' . $selectedYear . '.appraisal_id')
              ->where('appraisals_' . $selectedYear . '.department_id', $departmentID);
          })
          ->where('question_id', $sQuestion->question_id)
          ->avg('score');

        $sQuestion->average_score = number_format($averageScores, 2);
      }
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {

        $sidQuestions = FormQuestions::where('table_initials', 'SID')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        foreach ($sidQuestions as $sidQuestion) {
          $averageScore = AppraisalAnswers::where('question_id', $sidQuestion->question_id)
            ->avg('score');

          $sidQuestion->average_score = number_format($averageScore, 2);
        }

        $srQuestions = FormQuestions::where('table_initials', 'SR')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        foreach ($srQuestions as $srQuestion) {
          $averageScore = AppraisalAnswers::where('question_id', $srQuestion->question_id)
            ->avg('score');

          $srQuestion->average_score = number_format($averageScore, 2);
        }

        $sQuestions = FormQuestions::where('table_initials', 'S')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        foreach ($sQuestions as $sQuestion) {
          $averageScore = AppraisalAnswers::where('question_id', $sQuestion->question_id)
            ->avg('score');

          $sQuestion->average_score = number_format($averageScore, 2);
        }
      } else {
        return response()->json(['success' => false]);
      }
    }

    return response()->json([
      'success' => true,
      'sid' => $sidQuestions,
      'sr' => $srQuestions,
      's' => $sQuestions,
    ]);
  }

  public function loadICQuestions(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $departmentID = $request->input('departmentID');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    Log::debug('LOAD IC QUESTIONS: Selected Year from Request: ' . $selectedYear);

    if ($selectedYear) {
      $table = 'form_questions_' . $selectedYear;
      $icAnswersTable = 'appraisal_answers_' . $selectedYear;

      Log::debug('Selected Year Condition');
      $icQuestions = FormQuestions::from($table)
        ->where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($icQuestions as $icQuestion) {
        $averageScores = AppraisalAnswers::from($icAnswersTable)
          ->join('appraisals_' . $selectedYear, function ($join) use ($departmentID, $selectedYear) {
            $join->on('appraisal_answers_' . $selectedYear . '.appraisal_id', '=', 'appraisals_' . $selectedYear . '.appraisal_id')
              ->where('appraisals_' . $selectedYear . '.department_id', $departmentID);
          })
          ->where('question_id', $icQuestion->question_id)
          ->avg('score');

        $icQuestion->average_score = number_format($averageScores, 2);
      }
    } else {
      Log::debug('Active Year Condition');
      $icQuestions = FormQuestions::where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($icQuestions as $icQuestion) {
        $averageScore = AppraisalAnswers::where('question_id', $icQuestion->question_id)
          ->whereHas('appraisal', function ($query) use ($departmentID) {
            $query->where('department_id', $departmentID);
          })
          ->avg('score');

        $icQuestion->average_score = number_format($averageScore, 2);
      }
    }

    return response()->json([
      'success' => true,
      'ic' => $icQuestions
    ]);
  }

  public function loadCards(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $departmentID = $request->input('departmentID');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $appraisalsTable = 'appraisals_' . $selectedYear;
      $finalTable = 'final_scores_' . $selectedYear;

      $totalPermanentEmployees = Appraisals::from($appraisalsTable)
        ->where('department_id', $departmentID)
        ->count();

      $totalAppraisals = FinalScores::from($finalTable)
        ->where('department_id', $departmentID)
        ->count();

      $totalPermanentEmployees = ($totalPermanentEmployees > 0) ? ($totalPermanentEmployees / 4) : $totalPermanentEmployees;

      $avgTotalScore = FinalScores::where('department_id', $departmentID)
        ->avg('final_score');
      $avgTotalScore = round($avgTotalScore, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {

        $totalPermanentEmployees = Appraisals::where('department_id', $departmentID)->count();

        $totalAppraisals = FinalScores::where('department_id', $departmentID)->count();

        $totalPermanentEmployees = ($totalPermanentEmployees > 0) ? ($totalPermanentEmployees / 4) : $totalPermanentEmployees;

        $avgTotalScore = FinalScores::where('department_id', $departmentID)->avg('final_score');
        $avgTotalScore = round($avgTotalScore, 2);
      } else {
        return response()->json(['success' => false]);
      }
    }

    return response()->json([
      'success' => true,
      'totalAppraisals' => $totalAppraisals,
      'totalPermanentEmployees' => $totalPermanentEmployees,
      'avgTotalScore' => $avgTotalScore
    ]);
  }

  public function loadPointsSystem(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $departmentID = $request->input('departmentID');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $table = 'final_scores_' . $selectedYear;

      $outstanding = FinalScores::from($table)
        ->where('department_id', $departmentID)
        ->whereBetween('final_score', [4.85, 5.00])
        ->with('employee')
        ->orderBy('final_score')
        ->get();

      $verySatisfactory = FinalScores::from($table)
        ->where('department_id', $departmentID)
        ->whereBetween('final_score', [4.25, 4.84])
        ->with('employee')
        ->orderBy('final_score')
        ->get();

      $satisfactory = FinalScores::from($table)
        ->where('department_id', $departmentID)
        ->whereBetween('final_score', [3.50, 4.24])
        ->with('employee')
        ->orderBy('final_score')
        ->get();

      $fair = FinalScores::from($table)
        ->where('department_id', $departmentID)
        ->whereBetween('final_score', [2.75, 3.49])
        ->with('employee')
        ->orderBy('final_score')
        ->get();

      $poor = FinalScores::from($table)
        ->where('department_id', $departmentID)
        ->where('final_score', '<', 2.75)
        ->with('employee')
        ->orderBy('final_score')
        ->get();
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {

        $outstanding = FinalScores::where('department_id', $departmentID)
          ->whereBetween('final_score', [4.85, 5.00])
          ->with('employee')
          ->orderBy('final_score')
          ->get();

        $verySatisfactory = FinalScores::where('department_id', $departmentID)
          ->whereBetween('final_score', [4.25, 4.84])
          ->with('employee')
          ->orderBy('final_score')
          ->get();

        $satisfactory = FinalScores::where('department_id', $departmentID)
          ->whereBetween('final_score', [3.50, 4.24])
          ->with('employee')
          ->orderBy('final_score')
          ->get();

        $fair = FinalScores::where('department_id', $departmentID)
          ->whereBetween('final_score', [2.75, 3.49])
          ->with('employee')
          ->orderBy('final_score')
          ->get();

        $poor = FinalScores::where('department_id', $departmentID)
          ->where('final_score', '<', 2.75)
          ->with('employee')
          ->orderBy('final_score')
          ->get();
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'outstanding' => $outstanding,
      'verySatisfactory' => $verySatisfactory,
      'satisfactory' => $satisfactory,
      'fair' => $fair,
      'poor' => $poor
    ]);
  }

  public function getFinalScoresPerDepartment(Request $request)
  {
    try {
      $departmentID = $request->input('departmentID');
      Log::info('Department ID: ' . $departmentID);

      $evaluationYears = EvalYear::all();
      $scoresByDepartment = [];

      foreach ($evaluationYears as $year) {
        $table = 'final_scores_' . $year->sy_start . '_' . $year->sy_end;

        $scores = DB::table($table)
          ->select('department_id', DB::raw('AVG(final_score) as total_score'))
          ->where('department_id', $departmentID)
          ->groupBy('department_id')
          ->get();
        $scoresByDepartment[$year->sy_start . '-' . $year->sy_end] = $scores;
        // Log::info('Scores by Department: ' . json_encode($scoresByDepartment));
      }

      return response()->json([
        'success' => true,
        'scoresByDepartment' => $scoresByDepartment,
      ]);
      
    } catch (\Exception $e) {
      // Log the exception along with department ID and scores by department
      Log::error('Error in getFinalScoresPerDepartment: ' . $e->getMessage());
      Log::info('Department ID: ' . $departmentID);
      Log::info('Scores by Department: ' . json_encode($scoresByDepartment));

      // You can handle the exception as needed, e.g., return an error response
      return response()->json([
        'success' => false,
        'error' => 'An error occurred while fetching final scores.',
      ], 500); // 500 indicates a server error
    }
  }
}

