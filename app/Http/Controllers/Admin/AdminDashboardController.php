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
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    // Log::debug('Load Cards Called');
    $activeEvalYear = EvalYear::where('status', 'active')->first();
    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $schoolYear = str_replace('_', '-', $selectedYear);

      $appraisalsTable = 'appraisals_' . $selectedYear;
      $finalTable = 'final_scores_' . $selectedYear;

      $totalPermanentEmployees = Appraisals::from($appraisalsTable)
        ->count();

      $totalAppraisals = FinalScores::from($finalTable)
        ->count();

      $totalPermanentEmployees = ($totalPermanentEmployees > 0) ? ($totalPermanentEmployees / 4) : $totalPermanentEmployees;

      $avgTotalScore = FinalScores::from($finalTable)->avg('final_score');
      $avgTotalScore = round($avgTotalScore, 2);
    } else if ($activeEvalYear) {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $schoolYear = $activeEvalYear->sy_start . "-" . $activeEvalYear->sy_end;
        $totalPermanentEmployees = Appraisals::count();

        $totalAppraisals = FinalScores::count();

        $totalPermanentEmployees = ($totalPermanentEmployees > 0) ? ($totalPermanentEmployees / 4) : $totalPermanentEmployees;

        $avgTotalScore = FinalScores::avg('final_score');
        $avgTotalScore = round($avgTotalScore, 2);
      } else {
        return response()->json(['success' => false]);
      }
    } else {
      return response()->json(['success' => false]);
    }

    return response()->json([
      'success' => true,
      'schoolYear' => $schoolYear,
      'totalAppraisals' => $totalAppraisals,
      'totalPermanentEmployees' => $totalPermanentEmployees,
      'avgTotalScore' => $avgTotalScore
    ]);
  }

  public function loadDepartmentTable(Request $request)
  {
    if (session()->has('account_id')) {
      $search = $request->input('search');
      $page = $request->input('page');

      $perPage = 10;
      $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
        ->orderBy('department_name')
        ->paginate($perPage, ['*'], 'page', $page);

      return response()->json(['success' => true, 'departments' => $departments]);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadICQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

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
      $totalQuestions = count($icQuestions);

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
        $totalQuestions = count($icQuestions);

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

        // Log::info('Table Name: ' . $tableName);
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
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $data = [];
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

    foreach ($schoolYears as $evalYear) {
      $schoolYear = $evalYear->sy_start . '_' . $evalYear->sy_end;
      $table = 'final_scores_' . $schoolYear;

      $outstanding = FinalScores::from($table)
        ->whereBetween('final_score', [4.85, 5.00])
        ->count();

      $verySatisfactory = FinalScores::from($table)
        ->whereBetween('final_score', [4.25, 4.84])
        ->count();

      $satisfactory = FinalScores::from($table)
        ->whereBetween('final_score', [3.50, 4.24])
        ->count();

      $fair = FinalScores::from($table)
        ->whereBetween('final_score', [2.75, 3.49])
        ->count();

      $poor = FinalScores::from($table)
        ->where('final_score', '<', 2.75)
        ->count();

      $data[$schoolYear] = [
        $outstanding, $verySatisfactory, $satisfactory, $fair, $poor,
      ];
    }

    return response()->json([
      'success' => true,
      'data' => $data,
    ]);
  }

  public function getDepartmentalTrends()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $departments = Departments::orderBy('department_name', 'asc')->get();
    $evaluationYears = EvalYear::orderBy('sy_start', 'asc')->get();

    $chartData = [
      'labels' => [],     // Evaluation Years
      'datasets' => [],   // Data points for each department
    ];

    // Initialize an empty array to store scores for each department
    $departmentScores = [];
    foreach ($departments as $department) {
      $departmentScores[$department->department_name] = [];
    }

    foreach ($evaluationYears as $year) {
      $table = 'final_scores_' . $year->sy_start . '_' . $year->sy_end;

      $scoresForYear = [];

      foreach ($departments as $department) {
        $departmentID = $department->department_id;
        $departmentName = $department->department_name;

        $finalScore = DB::table($table)
          ->whereIn('department_id', [$departmentID])
          ->select(DB::raw('AVG(final_score) as average_score'))
          ->first();

        // Log::debug(json_encode($finalScore));

        // Check if $finalScore is null, and set it to 0 if it is
        $score = $finalScore && $finalScore->average_score !== null ? $finalScore->average_score : 0;

        $scoresForYear[$departmentName] = $score;
        $departmentScores[$departmentName][] = $score;
      }

      // Store evaluation years as labels
      $chartData['labels'][] = $year->sy_start . '-' . $year->sy_end;

      // Store average scores for each department in datasets
      foreach ($departments as $department) {
        $departmentName = $department->department_name;

        $chartData['datasets'][$departmentName] = [
          'label' => $departmentName,
          'data' => $departmentScores[$departmentName],
          // Additional dataset configurations for line chart (e.g., colors, etc.) can be added here
        ];
      }
    }

    return response()->json([
      'success' => true,
      'data' => $chartData,
    ]);
  }


  public function getFinalScoresPerYear()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $evaluationYears = EvalYear::orderBy('sy_start', 'asc')->get();
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

    //Log::info('$scoresPerYear');
    //Log::info($scoresPerYear);

    // Log the result (You can use Laravel's Log::info or write to a log file)
    return response()->json([
      'success' => true,
      'scoresPerYear' => $scoresPerYear,
    ]);
  }

  public function loadEmployees(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $perPage = 10;
    $search = $request->input('search');
    $page = $request->input('page', 1);

    $query = Accounts::where('type', 'PE')->with('employee.department');

    if (!empty($search)) {
      $query->where('email', 'LIKE', '%' . $search . '%');
    }

    $employees = $query->paginate($perPage, ['*'], 'page', $page);

    return response()->json(['success' => true, 'employees' => $employees]);
  }

  // CHARTS

  public function loadICChart()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $schoolYearsData = [];
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

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

  public function loadSIDQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $formQuestionsTable = 'form_questions_' . $selectedYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $selectedYear;
      $appraisalsTable = 'appraisals_' . $selectedYear;

      $sidQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $totalAverageScore = 0;
      $totalQuestions = count($sidQuestions);

      foreach ($sidQuestions as $sidQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $sidQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");

        $sidQuestion->average_score = number_format($averageScore, 2);

        if (!is_null($averageScore)) {
          $totalAverageScore += $averageScore;
        }
      }

      $schoolYear = $selectedYear;
      $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $sidQuestions = FormQuestions::where('table_initials', 'SID')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        $totalAverageScore = 0;
        $totalQuestions = count($sidQuestions);

        foreach ($sidQuestions as $sidQuestion) {
          $averageScore = $sidQuestion->appraisalAnswers()
            ->whereHas('appraisal', function ($query) {
              $query->where('date_submitted', 'IS NOT', null);
            })
            ->avg('score');


          $sidQuestion->average_score = number_format($averageScore, 2);

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

        // Log::info('Table Name: ' . $tableName);
        $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'sid' => $sidQuestions,
      'total_avg_score' => $totalAverageScore,
      'school_year' => $schoolYear
    ]);
  }

  public function loadSIDChart()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $schoolYearsData = [];
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

    foreach ($schoolYears as $evalYear) {
      $schoolYear = $evalYear->sy_start . '_' . $evalYear->sy_end;
      $formQuestionsTable = 'form_questions_' . $schoolYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $schoolYear;
      $appraisalsTable = 'appraisals_' . $schoolYear;

      $sidQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $questionsAndScores = [];

      foreach ($sidQuestions as $sidQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $sidQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");


        $questionsAndScores[$sidQuestion->question_id] = [
          'question' => $sidQuestion->question,
          'average_score' => number_format($averageScore, 2),
        ];
      }

      $schoolYearsData[$schoolYear] = $questionsAndScores;

      $totalAverageScore = 0;
      $totalQuestions = count($sidQuestions);

      foreach ($questionsAndScores as $questionData) {
        $totalAverageScore += floatval($questionData['average_score']);
      }

      $totalAverageScore = $totalQuestions > 0 ? $totalAverageScore / $totalQuestions : 0;
      $schoolYearsData[$schoolYear]['total_average_score'] = number_format($totalAverageScore, 2);
    }

    return response()->json(['success' => true, 'data' => $schoolYearsData]);
  }

  public function loadSRQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $formQuestionsTable = 'form_questions_' . $selectedYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $selectedYear;
      $appraisalsTable = 'appraisals_' . $selectedYear;

      $srQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $totalAverageScore = 0;
      $totalQuestions = count($srQuestions);

      foreach ($srQuestions as $srQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $srQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");

        $srQuestion->average_score = number_format($averageScore, 2);

        if (!is_null($averageScore)) {
          $totalAverageScore += $averageScore;
        }
      }

      $schoolYear = $selectedYear;
      $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $srQuestions = FormQuestions::where('table_initials', 'SR')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        $totalAverageScore = 0;
        $totalQuestions = count($srQuestions);

        foreach ($srQuestions as $srQuestion) {
          $averageScore = $srQuestion->appraisalAnswers()
            ->whereHas('appraisal', function ($query) {
              $query->where('date_submitted', 'IS NOT', null);
            })
            ->avg('score');


          $srQuestion->average_score = number_format($averageScore, 2);

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

        // Log::info('Table Name: ' . $tableName);
        $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'sr' => $srQuestions,
      'total_avg_score' => $totalAverageScore,
      'school_year' => $schoolYear
    ]);
  }

  public function loadSRChart()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $schoolYearsData = [];
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

    foreach ($schoolYears as $evalYear) {
      $schoolYear = $evalYear->sy_start . '_' . $evalYear->sy_end;
      $formQuestionsTable = 'form_questions_' . $schoolYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $schoolYear;
      $appraisalsTable = 'appraisals_' . $schoolYear;

      $srQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $questionsAndScores = [];

      foreach ($srQuestions as $srQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $srQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");


        $questionsAndScores[$srQuestion->question_id] = [
          'question' => $srQuestion->question,
          'average_score' => number_format($averageScore, 2),
        ];
      }

      $schoolYearsData[$schoolYear] = $questionsAndScores;

      $totalAverageScore = 0;
      $totalQuestions = count($srQuestions);

      foreach ($questionsAndScores as $questionData) {
        $totalAverageScore += floatval($questionData['average_score']);
      }

      $totalAverageScore = $totalQuestions > 0 ? $totalAverageScore / $totalQuestions : 0;
      $schoolYearsData[$schoolYear]['total_average_score'] = number_format($totalAverageScore, 2);
    }

    return response()->json(['success' => true, 'data' => $schoolYearsData]);
  }

  public function loadSQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $selectedYear = $request->input('selectedYear');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $formQuestionsTable = 'form_questions_' . $selectedYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $selectedYear;
      $appraisalsTable = 'appraisals_' . $selectedYear;

      $sQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $totalAverageScore = 0;
      $totalQuestions = count($sQuestions);

      foreach ($sQuestions as $sQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $sQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");

        $sQuestion->average_score = number_format($averageScore, 2);

        if (!is_null($averageScore)) {
          $totalAverageScore += $averageScore;
        }
      }

      $schoolYear = $selectedYear;
      $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $sQuestions = FormQuestions::where('table_initials', 'S')
          ->where('status', 'active')
          ->orderBy('question_order')
          ->get();

        $totalAverageScore = 0;
        $totalQuestions = count($sQuestions);

        foreach ($sQuestions as $sQuestion) {
          $averageScore = $sQuestion->appraisalAnswers()
            ->whereHas('appraisal', function ($query) {
              $query->where('date_submitted', 'IS NOT', null);
            })
            ->avg('score');


          $sQuestion->average_score = number_format($averageScore, 2);

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

        // Log::info('Table Name: ' . $tableName);
        $totalAverageScore = number_format($totalAverageScore / $totalQuestions, 2);
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      's' => $sQuestions,
      'total_avg_score' => $totalAverageScore,
      'school_year' => $schoolYear
    ]);
  }

  public function loadSChart()
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $schoolYearsData = [];
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

    foreach ($schoolYears as $evalYear) {
      $schoolYear = $evalYear->sy_start . '_' . $evalYear->sy_end;
      $formQuestionsTable = 'form_questions_' . $schoolYear;
      $appraisalAnswersTable = 'appraisal_answers_' . $schoolYear;
      $appraisalsTable = 'appraisals_' . $schoolYear;

      $sQuestions = FormQuestions::from($formQuestionsTable)
        ->where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $questionsAndScores = [];

      foreach ($sQuestions as $sQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $sQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");


        $questionsAndScores[$sQuestion->question_id] = [
          'question' => $sQuestion->question,
          'average_score' => number_format($averageScore, 2),
        ];
      }

      $schoolYearsData[$schoolYear] = $questionsAndScores;

      $totalAverageScore = 0;
      $totalQuestions = count($sQuestions);

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
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $selectedYear = $request->input('selectedYear');
    $questionID = $request->input('questionID');
    $formQuestionsTable = 'form_questions_' . $selectedYear;
    $appraisalAnswersTable = 'appraisal_answers_' . $selectedYear;
    $appraisalsTable = 'appraisals_' . $selectedYear;

    $page = $request->input('page', 1);
    $perPage = 10;

    $question = DB::table($formQuestionsTable)
      ->where('question_id', $questionID)
      ->first();

    $query = DB::table($appraisalAnswersTable)
      ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
      ->join('employees', $appraisalsTable . '.employee_id', '=', 'employees.employee_id')
      ->where($appraisalsTable . '.date_submitted', 'IS NOT', null)
      ->where($appraisalAnswersTable . '.question_id', $question->question_id)
      ->select(
        DB::raw('ROUND(AVG(' . $appraisalAnswersTable . '.score), 2) as score'),
        'employees.first_name',
        'employees.last_name',
        'employees.employee_id'
      )
      ->groupBy('employees.first_name', 'employees.last_name', 'employees.employee_id');

    $questionAnswers = $query->paginate($perPage, null, 'page', $page);

    return response()->json(['success' => true, 'question' => $question, 'questionAnswers' => $questionAnswers]);
  }


  public function loadPointCategory(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $schoolYear = $request->input('selectedYear');
    $category = $request->input('category');
    $page = $request->input('page', 1);
    $perPage = 10;

    $data = [];

    $table = 'final_scores_' . $schoolYear;

    $categoryData = [];

    switch ($category) {
      case 'Outstanding':
        $categoryData = FinalScores::from($table)
          ->whereBetween('final_score', [4.85, 5.00])
          ->with('employee')
          ->select('employee_id', 'final_score')
          ->paginate($perPage, null, 'page', $page);
        break;
      case 'Very Satisfactory':
        $categoryData = FinalScores::from($table)
          ->whereBetween('final_score', [4.25, 4.84])
          ->with('employee')
          ->select('employee_id', 'final_score')
          ->paginate($perPage, null, 'page', $page);
        break;
      case 'Satisfactory':
        $categoryData = FinalScores::from($table)
          ->whereBetween('final_score', [3.50, 4.24])
          ->with('employee')
          ->select('employee_id', 'final_score')
          ->paginate($perPage, null, 'page', $page);
        break;
      case 'Fair':
        $categoryData = FinalScores::from($table)
          ->whereBetween('final_score', [2.75, 3.49])
          ->with('employee')
          ->select('employee_id', 'final_score')
          ->paginate($perPage, null, 'page', $page);
        break;
      case 'Poor':
        $categoryData = FinalScores::from($table)
          ->where('final_score', '<', 2.75)
          ->with('employee')
          ->select('employee_id', 'final_score')
          ->paginate($perPage, null, 'page', $page);
        break;
      default:
        break;
    }

    $data = $categoryData;

    return response()->json([
      'success' => true,
      'data' => $data,
    ]);
  }

  public function printAdminDashboard()
  {
    if (session()->has('account_id')) {
      // $evaluationYears = EvalYear::all();
      // $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      return view('print-save.admin_dashboard_print');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

}
