<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\Departments;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    if ($selectedYear) {
      $sid = FormQuestions::where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $sr = FormQuestions::where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $s = FormQuestions::where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

    } else {
      $sid = FormQuestions::where('table_initials', 'SID')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $sr = FormQuestions::where('table_initials', 'SR')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();

      $s = FormQuestions::where('table_initials', 'S')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();
    }

    return response()->json([
      'success' => true,
      'sid' => $sid,
      'sr' => $sr,
      's' => $s,
    ]);
  }

  public function loadICQuestions(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $departmentID = $request->input('departmentID');

    if ($selectedYear == 'null') {
      $selectedYear = null;
    }
    
    Log::debug('Selected Year from Request: ' . $selectedYear);

    if ($selectedYear) {
      Log::debug('Selected Year Condition');
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

    if ($selectedYear) {
      $totalPermanentEmployees = FinalScores::where('department_id', $departmentID)->count();

      $sumTotalScore = FinalScores::where('department_id', $departmentID)->sum('final_score');

      $avgTotalScore = ($totalPermanentEmployees > 0) ? ($sumTotalScore / $totalPermanentEmployees) : 0;
      $avgTotalScore = round($avgTotalScore, 2);

    } else {
      $totalPermanentEmployees = FinalScores::where('department_id', $departmentID)->count();

      $sumTotalScore = FinalScores::where('department_id', $departmentID)->sum('final_score');

      $avgTotalScore = ($totalPermanentEmployees > 0) ? ($sumTotalScore / $totalPermanentEmployees) : 0;
      $avgTotalScore = round($avgTotalScore, 2);

    }

    return response()->json([
      'success' => true,
      'totalPermanentEmployees' => $totalPermanentEmployees,
      'avgTotalScore' => $avgTotalScore
    ]);
  }

  public function loadPointsSystem(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $departmentID = $request->input('departmentID');

    if ($selectedYear) {
      $outstanding = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [4.85, 5.00])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $verySatisfactory = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [4.25, 4.84])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $satisfactory = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [3.50, 4.24])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $fair = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [2.75, 3.49])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $poor = FinalScores::where('department_id', $departmentID)
        ->where('final_score', '<', 2.75)
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

    } else {
      $outstanding = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [4.85, 5.00])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $verySatisfactory = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [4.25, 4.84])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $satisfactory = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [3.50, 4.24])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $fair = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_score', [2.75, 3.49])
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();

      $poor = FinalScores::where('department_id', $departmentID)
        ->where('final_score', '<', 2.75)
        ->with('employee')
        ->orderBy('final_score') // Add orderBy clause
        ->get();
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
}