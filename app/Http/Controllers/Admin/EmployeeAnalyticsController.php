<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\AppraisalAnswers;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\FormQuestions;
use App\Models\KRA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeAnalyticsController extends Controller
{
  public function displayEmployeeAnalytics()
  {
    if (session()->has('account_id')) {
      return view('admin-pages.admin_employee_analytics');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getEmployeeInformation(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');

    $employee = Employees::with('department')->find($employeeID);
    $account = Accounts::find($employee->account_id);

    if ($employee) {
      return response()->json([
        'success' => true,
        'account' => $account,
        'employee' => $employee
      ]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Employee not found.'
      ]);
    }
  }

  public function loadSIDQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $selectedYear = $request->input('selectedYear');
    $employeeID = $request->input('employeeID');;

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
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['self-evaluation', 'is evaluation'])
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
            ->whereHas('appraisal', function ($query) use ($employeeID) {
              $query->where('employee_id', $employeeID)
                ->whereIn('evaluation_type', ['self-evaluation', 'is evaluation'])
                ->where('date_submitted', 'IS NOT', null);
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

  public function loadSIDChart(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employee_id = $request->input('employeeID');
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
          ->where("$appraisalsTable.employee_id", $employee_id)
          ->whereIn("$appraisalsTable.evaluation_type", ['self-evaluation', 'is evaluation'])
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

    $employeeID = $request->input('employeeID');
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
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['self-evaluation', 'is evaluation'])
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
            ->whereHas('appraisal', function ($query) use ($employeeID) {
              $query->where('employee_id', $employeeID)
                ->whereIn('evaluation_type', ['self-evaluation', 'is evaluation'])
                ->where('date_submitted', 'IS NOT', null);
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

        Log::info('Table Name: ' . $tableName);
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

  public function loadSRChart(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
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
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['self-evaluation', 'is evaluation'])
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $srQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");


        $questionsAndScores[$srQuestion->question_id] = [
          'question' => $srQuestion->question,
          'average_score' => number_format($averageScore, 2),
        ];
      }

      $schoolYearsData[$schoolYear] = $questionsAndScores;

      // Calculate the total average score for this year
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

  public function loadICChart(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
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
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['internal customer 1', 'internal customer 2'])
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

  public function loadSChart(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
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
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['self-evaluation', 'is evaluation'])
          ->where("$appraisalsTable.date_submitted", 'IS NOT', null)
          ->where("$appraisalAnswersTable.question_id", $sQuestion->question_id)
          ->avg("$appraisalAnswersTable.score");


        $questionsAndScores[$sQuestion->question_id] = [
          'question' => $sQuestion->question,
          'average_score' => number_format($averageScore, 2),
        ];
      }

      $schoolYearsData[$schoolYear] = $questionsAndScores;

      // Calculate the total average score for this year
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

  public function loadSQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
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
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['self-evaluation', 'is evaluation'])
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
        $totalQuestions = count($sQuestions); // Get the total number of questions

        foreach ($sQuestions as $sQuestion) {
          $averageScore = $sQuestion->appraisalAnswers()
            ->whereHas('appraisal', function ($query) use ($employeeID) {
              $query->where('employee_id', $employeeID)
                ->whereIn('evaluation_type', ['self-evaluation', 'is evaluation'])
                ->where('date_submitted', 'IS NOT', null);
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

        Log::info('Table Name: ' . $tableName);
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

  public function loadICQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
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

      Log::info($icQuestions);

      $totalAverageScore = 0;
      $totalQuestions = count($icQuestions);

      foreach ($icQuestions as $icQuestion) {
        $averageScore = AppraisalAnswers::from($appraisalAnswersTable)
          ->join($appraisalsTable, $appraisalsTable . '.appraisal_id', '=', $appraisalAnswersTable . '.appraisal_id')
          ->where("$appraisalsTable.employee_id", $employeeID)
          ->whereIn("$appraisalsTable.evaluation_type", ['internal customer 1', 'internal customer 2'])
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
            ->whereHas('appraisal', function ($query) use ($employeeID) {
              $query->where('employee_id', $employeeID)
                ->whereIn('evaluation_type', ['internal customer 1', 'internal customer 2'])
                ->where('date_submitted', 'IS NOT', null);
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

  public function loadYearlyTrend(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

    $scoresPerYear = [];

    foreach ($schoolYears as $schoolYear) {
      $table = 'final_scores_' . $schoolYear->sy_start . '_' . $schoolYear->sy_end;

      $scores = FinalScores::from($table)
        ->where('employee_id', $employeeID)
        ->select('final_score')
        ->get();

      $scoresPerYear[$schoolYear->sy_start . '-' . $schoolYear->sy_end] = $scores;
    }

    return response()->json([
      'success' => true,
      'scoresPerYear' => $scoresPerYear,
    ]);
  }

  public function loadKRATrend(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeID = $request->input('employeeID');
    $schoolYears = EvalYear::orderBy('sy_start', 'asc')->get();

    $scoresPerYear = [];

    foreach ($schoolYears as $schoolYear) {
      $table = 'appraisals_' . $schoolYear->sy_start . '_' . $schoolYear->sy_end;

      $scores = Appraisals::from($table)
        ->where('employee_id', $employeeID)
        ->whereIn('evaluation_type', ['self-evaluation', 'is evaluation'])
        ->where('date_submitted', 'IS NOT', null)
        ->avg('kra_score');

      $scores = number_format($scores, 2);
      $scoresPerYear[$schoolYear->sy_start . '-' . $schoolYear->sy_end] = $scores;
    }

    return response()->json([
      'success' => true,
      'scoresPerYear' => $scoresPerYear,
    ]);
  }

  public function loadKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $selectedYear = $request->input('selectedYear');
    $employeeID = $request->input('employeeID');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }

    if ($selectedYear) {
      $kraTable = 'kras_' . $selectedYear;
      $appraisalsTable = 'appraisals_' . $selectedYear;

      $appraisalsWithKRAs = Appraisals::from($appraisalsTable)
        ->where('employee_id', $employeeID)
        ->whereIn('evaluation_type', ['self evaluation', 'is evaluation'])
        ->where('date_submitted', 'IS NOT', null)
        ->join($kraTable, $appraisalsTable . '.appraisal_id', '=', $kraTable . '.appraisal_id')
        ->get();

      if ($appraisalsWithKRAs->isEmpty()) {
        return response()->json(['success' => false]);
      }
    } else {
      if (Appraisals::tableExists() && KRA::tableExists()) {
        $appraisalsWithKRAs = Appraisals::where('employee_id', $employeeID)
          ->whereIn('evaluation_type', ['self evaluation', 'is evaluation'])
          ->where('date_submitted', 'IS NOT', null)
          ->with('kras')
          ->get();
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'appraisals' => $appraisalsWithKRAs,
    ]);
  }
}
