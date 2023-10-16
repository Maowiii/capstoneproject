<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\AppraisalAnswers;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class AdminDashboardController extends Controller
{
  public function displayAdminDashboard()
  {
    if (session()->has('account_id')) {
      $evaluationYears = EvalYear::all();
      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      return view('admin-pages.admin_dashboard', compact('evaluationYears', 'activeEvalYear'));
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadCards(Request $request)
  {
    Log::debug('Load Cards Called');
    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $appraisalsTable = 'appraisals_' . $selectedYear;
      $finalTable = 'final_scores_' . $selectedYear;

      $totalPermanentEmployees = Appraisals::from($appraisalsTable)
        ->count();

      $totalAppraisals = FinalScores::from($finalTable)
        ->count();

      $totalPermanentEmployees = ($totalPermanentEmployees > 0) ? ($totalPermanentEmployees / 4) : $totalPermanentEmployees;

      $avgTotalScore = FinalScores::from($finalTable)->avg('final_score');
      $avgTotalScore = round($avgTotalScore, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {

        $totalPermanentEmployees = Appraisals::count();

        $totalAppraisals = FinalScores::count();

        $totalPermanentEmployees = ($totalPermanentEmployees > 0) ? ($totalPermanentEmployees / 4) : $totalPermanentEmployees;

        $avgTotalScore = FinalScores::avg('final_score');
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

  public function loadDepartmentTable(Request $request)
  {
    if (session()->has('account_id')) {
      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      $search = $request->input('search');
      $selectedYear = $request->input('selectedYear');
      $page = $request->input('page');

      if ($selectedYear == 'null') {
        $selectedYear = null;
      }

      if ($selectedYear) {
        $perPage = 20;
        $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
          ->orderBy('department_name')
          ->get();

        $averageScoresArray = [];
        $finalTable = 'final_scores_' . $selectedYear;

        foreach ($departments as $department) {
          $averageScore = FinalScores::from($finalTable)
            ->where('department_id', $department->department_id)
            ->avg('final_score');

          $department->average_score = number_format($averageScore, 2);

          $averageScoresArray[] = ['department' => $department, 'average_score' => $department->average_score];
        }

        usort($averageScoresArray, function ($a, $b) {
          return $b['average_score'] <=> $a['average_score'];
        });

        $rank = ($page - 1) * $perPage + 1;
        $currentPageItems = array_slice($averageScoresArray, ($page - 1) * $perPage, $perPage);
        foreach ($currentPageItems as $averageScoreItem) {
          $department = $averageScoreItem['department'];
          $department->rank = $rank;
          $rank++;
        }

        $departments = new LengthAwarePaginator(
          $currentPageItems,
          count($averageScoresArray),
          $perPage,
          $page,
          ['path' => $request->url(), 'query' => $request->query()]
        );
      } else {
        if (FinalScores::tableExists()) {
          $perPage = 20;
          $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
            ->orderBy('department_name')
            ->get();

          $averageScoresArray = [];

          foreach ($departments as $department) {
            $averageScore = FinalScores::where('department_id', $department->department_id)
              ->avg('final_score');

            $department->average_score = number_format($averageScore, 2);

            $averageScoresArray[] = ['department' => $department, 'average_score' => $department->average_score];
          }

          usort($averageScoresArray, function ($a, $b) {
            return $b['average_score'] <=> $a['average_score'];
          });

          $rank = ($page - 1) * $perPage + 1;
          $currentPageItems = array_slice($averageScoresArray, ($page - 1) * $perPage, $perPage);
          foreach ($currentPageItems as $averageScoreItem) {
            $department = $averageScoreItem['department'];
            $department->rank = $rank;
            $rank++;
          }

          $departments = new LengthAwarePaginator(
            $currentPageItems,
            count($averageScoresArray),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
          );
        } else {
          return response()->json(['success' => false]);
        }
      }

      return response()->json(['success' => true, 'departments' => $departments]);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }


  public function loadBCQuestions(Request $request)
  {
    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $sidQuestionsTable = 'form_questions_' . $selectedYear;
      $sidAnswersTable = 'appraisal_answers_' . $selectedYear;

      $sidQuestions = FormQuestions::from($sidQuestionsTable)
        ->where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($sidQuestions as $sidQuestion) {
        $averageScore = AppraisalAnswers::from($sidAnswersTable)
          ->where('question_id', $sidQuestion->question_id)
          ->avg('score');

        $sidQuestion->average_score = number_format($averageScore, 2);
      }

      $srQuestionsTable = 'form_questions_' . $selectedYear;
      $srAnswersTable = 'appraisal_answers_' . $selectedYear;

      $srQuestions = FormQuestions::from($srQuestionsTable)
        ->where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($srQuestions as $srQuestion) {
        $averageScore = AppraisalAnswers::from($srAnswersTable)
          ->where('question_id', $srQuestion->question_id)
          ->avg('score');

        $srQuestion->average_score = number_format($averageScore, 2);
      }

      $sQuestionsTable = 'form_questions_' . $selectedYear;
      $sAnswersTable = 'appraisal_answers_' . $selectedYear;

      $sQuestions = FormQuestions::from($sQuestionsTable)
        ->where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($sQuestions as $sQuestion) {
        $averageScore = AppraisalAnswers::from($sAnswersTable)
          ->where('question_id', $sQuestion->question_id)
          ->avg('score');

        $sQuestion->average_score = number_format($averageScore, 2);
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

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $formQuestionsTable = 'form_questions_' . $selectedYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $selectedYear;

      $icQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($icQuestions as $icQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->where('question_id', $icQuestion->question_id)
          ->avg('score');

        $icQuestion->average_score = number_format($averageScore, 2);
      }
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $icQuestions = FormQuestions::where('table_initials', 'IC')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        foreach ($icQuestions as $icQuestion) {
          $averageScore = AppraisalAnswers::where('question_id', $icQuestion->question_id)
            ->avg('score');

          $icQuestion->average_score = number_format($averageScore, 2);
        }
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'ic' => $icQuestions
    ]);
  }

  public function loadPointsSystem(Request $request)
  {
    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $table = 'final_scores_' . $selectedYear;

      $outstanding = FinalScores::from($table)
        ->whereBetween('final_score', [4.85, 5.00])
        ->with('employee')
        ->orderByDesc('final_score')
        ->get();

      $verySatisfactory = FinalScores::from($table)
        ->whereBetween('final_score', [4.25, 4.84])
        ->with('employee')
        ->orderByDesc('final_score')
        ->get();

      $satisfactory = FinalScores::from($table)
        ->whereBetween('final_score', [3.50, 4.24])
        ->with('employee')
        ->orderByDesc('final_score')
        ->get();

      $fair = FinalScores::from($table)
        ->whereBetween('final_score', [2.75, 3.49])
        ->with('employee')
        ->orderByDesc('final_score')
        ->get();

      $poor = FinalScores::from($table)
        ->where('final_score', '<', 2.75)
        ->with('employee')
        ->orderByDesc('final_score')
        ->get();
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $outstanding = FinalScores::whereBetween('final_score', [4.85, 5.00])
          ->with('employee')
          ->orderByDesc('final_score')
          ->get();

        $verySatisfactory = FinalScores::whereBetween('final_score', [4.25, 4.84])
          ->with('employee')
          ->orderByDesc('final_score')
          ->get();

        $satisfactory = FinalScores::whereBetween('final_score', [3.50, 4.24])
          ->with('employee')
          ->orderByDesc('final_score')
          ->get();

        $fair = FinalScores::whereBetween('final_score', [2.75, 3.49])
          ->with('employee')
          ->orderByDesc('final_score')
          ->get();

        $poor = FinalScores::where('final_score', '<', 2.75)
          ->with('employee')
          ->orderByDesc('final_score')
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
  public function getFinalScoresPerYear()
  {
    // Get all evaluation years, whether active or not
    $evaluationYears = EvalYear::all();
    $scoresPerYear = [];
    foreach ($evaluationYears as $year) {
      $table = 'final_scores_' . $year->sy_start . '_' . $year->sy_end;

      // Fetch the total final scores and count of employees who submitted for the current year
      $scores = DB::table($table)
        ->select(
          'employee_id',
          DB::raw('SUM(final_score) as total_score'),
          DB::raw('COUNT(DISTINCT employee_id) as employee_count')
        )
        ->groupBy('employee_id')
        ->get();

      $scoresPerYear[$year->sy_start . '-' . $year->sy_end] = $scores;
    }

    // Divide total scores by the number of employees who submitted for each year
    foreach ($scoresPerYear as $yearRange => $scores) {
      foreach ($scores as $score) {
        $score->total_score /= $score->employee_count;
      }
    }

    // Log the result (You can use Laravel's Log::info or write to a log file)
    Log::info('Computed Final Scores Per Year', $scoresPerYear);
    return response()->json([
      'success' => true,
      'scoresPerYear' => $scoresPerYear,
    ]);
  }

  public function loadEmployees(Request $request)
  {
    $perPage = 20;
    $search = $request->input('search');
    $page = $request->input('page', 1);

    $query = Accounts::where('type', 'PE')->with('employee.department');

    if (!empty($search)) {
      $query->where('email', 'LIKE', '%' . $search . '%');
    }

    $employees = $query->paginate($perPage, ['*'], 'page', $page);

    return response()->json(['success' => true, 'employees' => $employees]);
  }

  public function loadEmployeeTrends(Request $request)
  {
    $schoolYears = EvalYear::all()->map(function ($evalYear) {
      return $evalYear->sy_start . '_' . $evalYear->sy_end;
    })->toArray();

    $employeeID = $request->input('employeeID');

    $employee = Employees::find($employeeID);

    $finalScores = [];

    foreach ($schoolYears as $year) {
      $tableName = 'final_scores_' . $year;
      $score = FinalScores::from($tableName)->where('employee_id', $employeeID)->value('final_score');

      if ($score !== null) {
        $finalScores[$year] = $score;
      }
    }

    return response()->json(['success' => true, 'employee' => $employee, 'finalScores' => $finalScores]);
  }
}
