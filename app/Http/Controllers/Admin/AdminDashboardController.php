<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\EvalYear;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

  public function loadDepartmentTable(Request $request)
  {
    if (session()->has('account_id')) {

      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      $search = $request->input('search');
      $selectedYear = $request->input('selectedYear');
      $page = $request->input('page');

      if ($selectedYear) {
        if ($search) {
          $table = 'appraisals_' . $selectedYear;
          $appraisalModel = new Appraisals;
          $appraisalModel->setTable($table);

        } else {
          $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
            ->orderBy('department_name')
            ->paginate(20);

          return response()->json(['success' => true, 'departments' => $departments]);
        }

      } elseif ($activeEvalYear) {
        if ($search) {
          $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
            ->orderBy('department_name')
            ->paginate(20);

          return response()->json(['success' => true, 'departments' => $departments]);
        } else {
          $departments = Departments::orderBy('department_name')->paginate(20);

          return response()->json(['success' => true, 'departments' => $departments]);
        }
      } else {
        return response()->json(['success' => false]);
      }
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadBCQuestions(Request $request)
  {
    $selectedYear = $request->input('selectedYear');

    if ($selectedYear) {
      $sidQuestions = FormQuestions::where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($sidQuestions as $sidQuestion) {
        $averageScore = AppraisalAnswers::where('question_id', $sidQuestion->question_id)
          ->avg('score');

        $sidQuestion->average_score = number_format($averageScore, 2);
      }

      $sr = FormQuestions::where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $s = FormQuestions::where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();
    } else {
      Log::debug('Active Year Condition');

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
      $icQuestions = FormQuestions::where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      foreach ($icQuestions as $icQuestion) {
        $averageScore = AppraisalAnswers::where('question_id', $icQuestion->question_id)
          ->avg('score');

        $icQuestion->average_score = number_format($averageScore, 2);
      }
    }

    return response()->json([
      'success' => true,
      'ic' => $icQuestions
    ]);
  }
}