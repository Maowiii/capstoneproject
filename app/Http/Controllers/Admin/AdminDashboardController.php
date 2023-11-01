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
use App\Models\ScoreWeights;
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
      $appraisalsTable = 'appraisals_' . $selectedYear;

      $icQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $totalAverageScore = 0;
      $totalQuestions = count($icQuestions); // Get the total number of questions

      foreach ($icQuestions as $icQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $icQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");

        $icQuestion->average_score = number_format($averageScore, 2);

        if (!is_null($averageScore)) {
          $totalAverageScore += $averageScore;
        }
      }

      $schoolYear = $selectedYear;
      $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $icQuestions = FormQuestions::where('table_initials', 'IC')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        $totalAverageScore = 0;
        $totalQuestions = count($icQuestions); // Get the total number of questions

        foreach ($icQuestions as $icQuestion) {
          $averageScore = $icQuestion->appraisalAnswers()
            ->whereHas('appraisal', function ($query) {
              $query->where('date_submitted', 'IS NOT', null);
            })
            ->avg('score');


          $icQuestion->average_score = number_format($averageScore, 2);

          if (!is_null($averageScore)) {
            $totalAverageScore += $averageScore;
          }
        }

        $formQuestions = new FormQuestions;
        $tableName = $formQuestions->getTable();
        preg_match('/(\d{4})_(\d{4})/', $tableName, $matches);
        if (isset($matches[1])) {
          $schoolYear = $matches[1] . '_' . $matches[2];
        }

        Log::info('Table Name: ' . $tableName);
        $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'ic' => $icQuestions,
      'total_avg_score' => $totalAverageScore,
      'school_year' => $schoolYear
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
      // $scores = DB::table($table)
      //   ->select('employee_id',
      //     DB::raw('AVG(final_score) as total_score'),
      //     DB::raw('COUNT(DISTINCT employee_id) as employee_count'))
      //   ->groupBy('employee_id')
      //   ->get();

      $scores = DB::table($table)
        ->select(DB::raw('AVG(final_score) as total_score'))
        ->first();

      $scoresPerYear[$year->sy_start . '-' . $year->sy_end] = $scores;
    }

    // Divide total scores by the number of employees who submitted for each year
    // foreach ($scoresPerYear as $yearRange => $scores) {
    //   foreach ($scores as $score) {
    //     $score->total_score /= $score->employee_count;
    //   }
    // }

    Log::info('$scoresPerYear');
    Log::info($scoresPerYear);

    // Log the result (You can use Laravel's Log::info or write to a log file)
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
    $schoolYears = EvalYear::all()
      ->map(function ($evalYear) {
        $schoolYear = $evalYear->sy_start . '_' . $evalYear->sy_end;
        $evalId = $evalYear->eval_id;
        return [
          'school_year' => $schoolYear,
          'eval_id' => $evalId,
        ];
      })
      ->toArray();


    $employeeID = $request->input('employeeID');

    $employee = Employees::find($employeeID);
    Log::debug('School Years: ' . json_encode($schoolYears));
    Log::debug('Employee ID: ' . $employeeID);

    $finalScores = [];

    foreach ($schoolYears as $yearData) {
      $year = $yearData['school_year'];
      $tableName = 'final_scores_' . $year;
      $score = FinalScores::from($tableName)->where('employee_id', $employeeID)->value('final_score');

      if ($score !== null) {
        $finalScores[$year] = $score;
      }
    }

    $bhScores = [];

    foreach ($schoolYears as $yearData) {
      $year = $yearData['school_year'];
      $yearID = $yearData['eval_id'];
      $bhTable = 'appraisals_' . $year;

      $selfEvalScore = Appraisals::from($bhTable)
        ->where('employee_id', $employeeID)
        ->where('evaluation_type', 'self evaluation')
        ->value('bh_score');

      $isScore = Appraisals::from($bhTable)
        ->where('employee_id', $employeeID)
        ->where('evaluation_type', 'is evaluation')
        ->value('bh_score');

      $ic1Score = Appraisals::from($bhTable)
        ->where('employee_id', $employeeID)
        ->where('evaluation_type', 'internal customer 1')
        ->value('ic_score');

      $ic2Score = Appraisals::from($bhTable)
        ->where('employee_id', $employeeID)
        ->where('evaluation_type', 'internal customer 2')
        ->value('ic_score');

      if ($selfEvalScore !== null && $isScore !== null && $ic1Score !== null && $ic2Score !== null) {
        $selfEvalWeight = ScoreWeights::where('eval_id', $yearID)->value('self_eval_weight') / 100;
        $isWeight = ScoreWeights::where('eval_id', $yearID)->value('is_weight') / 100;
        $ic1Weight = ScoreWeights::where('eval_id', $yearID)->value('ic1_weight') / 100;
        $ic2Weight = ScoreWeights::where('eval_id', $yearID)->value('ic2_weight') / 100;

        $selfEvalScore *= $selfEvalWeight;
        $isScore *= $isWeight;
        $ic1Score *= $ic1Weight;
        $ic2Score *= $ic2Weight;

        $totalBHScore = $selfEvalScore + $isScore + $ic1Score + $ic2Score;
        $bhScores[$year] = $totalBHScore;
      }
    }

    $kraScores = [];
    foreach ($schoolYears as $yearData) {
      $year = $yearData['school_year'];
      $yearID = $yearData['eval_id'];
      $kraTable = 'appraisals_' . $year;

      $selfEvalKRAScore = Appraisals::from($kraTable)
        ->where('employee_id', $employeeID)
        ->where('evaluation_type', 'self evaluation')
        ->value('kra_score');

      $isKRAScore = Appraisals::from($kraTable)
        ->where('employee_id', $employeeID)
        ->where('evaluation_type', 'is evaluation')
        ->value('kra_score');

      if ($selfEvalKRAScore !== null && $isKRAScore !== null) {

        $selfEvalKRAScore *= .5;
        $isKRAScore *= .5;

        $totalKRAScore = $selfEvalKRAScore + $isKRAScore;
        $kraScores[$year] = $totalKRAScore;
      }
    }

    return response()->json(['success' => true, 'employee' => $employee, 'finalScores' => $finalScores, 'bhScores' => $bhScores, 'kraScores' => $kraScores]);
  }


  // CHARTS

  public function loadICChart()
  {
    $schoolYearsData = [];
    $schoolYears = EvalYear::all();

    foreach ($schoolYears as $evalYear) {
      $schoolYear = $evalYear->sy_start . '_' . $evalYear->sy_end;
      $formQuestionsTable = 'form_questions_' . $schoolYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $schoolYear;
      $appraisalsTable = 'appraisals_' . $schoolYear;

      $icQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $questionsAndScores = [];

      foreach ($icQuestions as $icQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $icQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");


        $questionsAndScores[$icQuestion->question_id] = [
          'question' => $icQuestion->question,
          'average_score' => number_format($averageScore, 2),
        ];
      }

      $schoolYearsData[$schoolYear] = $questionsAndScores;

      // Calculate the total average score for this year
      $totalAverageScore = 0;
      $totalQuestions = count($icQuestions);

      foreach ($questionsAndScores as $questionData) {
        $totalAverageScore += floatval($questionData['average_score']);
      }

      $totalAverageScore = $totalQuestions > 0 ? $totalAverageScore / $totalQuestions : 0;
      $schoolYearsData[$schoolYear]['total_average_score'] = number_format($totalAverageScore, 2);
    }

    return response()->json(['success' => true, 'data' => $schoolYearsData]);
  }

  public function viewScore(Request $request)
  {
    $selectedYear = $request->selectedYear;
    $questionID = $request->questionID;
    $formQuestionsTable = 'form_questions_' . $selectedYear;
    $appraisalAnswersTable = 'appraisal_answers_' . $selectedYear;
    $appraisalsTable = 'appraisals_' . $selectedYear;

    Log::info('Selected Year: ' . $selectedYear);
    Log::info('Question ID: ' . $questionID);
    Log::info('Form Questions Table: ' . $formQuestionsTable);
    Log::info('Appraisal Answers Table: ' . $appraisalAnswersTable);
    Log::info('Appraisals Table: ' . $appraisalsTable);

    $page = $request->input('page', 1);
    $perPage = 10;

    // $question = FormQuestions::from($formQuestionsTable)
    //   ->find($questionID);

    // $query = AppraisalAnswers::from($appraisalAnswersTable)
    //   ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
    //   ->join('employees', $appraisalsTable . '.employee_id', '=', 'employees.employee_id')
    //   ->where($appraisalsTable . '.date_submitted', 'IS NOT', null)
    //   ->where($formQuestionsTable . '.question_id', $question->question_id)
    //   ->select(
    //     $appraisalAnswersTable . '.score',
    //     'employees.first_name',
    //     'employees.last_name'
    //   );

    $question = DB::table($formQuestionsTable)
    ->where('question_id', $questionID)
    ->first();

    $query = DB::table($appraisalAnswersTable)
    ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
    ->join('employees', $appraisalsTable . '.employee_id', '=', 'employees.employee_id')
    ->where($appraisalsTable . '.date_submitted', 'IS NOT', null)
    ->where($appraisalAnswersTable . '.question_id', $question->question_id)
    ->select(
      $appraisalAnswersTable . '.score',
      'employees.first_name',
      'employees.last_name'
    );

    $questionAnswers = $query->paginate($perPage, null, 'page', $page);

    return response()->json(['success' => true, 'question' => $question, 'questionAnswers' => $questionAnswers]);
  }
}
