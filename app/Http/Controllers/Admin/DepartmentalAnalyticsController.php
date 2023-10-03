<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

  public function loadQuestions(Request $request)
  {
    $selectedYear = $request->input('selectedYear');

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

      $ic = FormQuestions::where('table_initials', 'IC')
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

      $ic = FormQuestions::where('table_initials', 'IC')
        ->where('status', 'active')
        ->orderBy('question_order')
        ->get();
    }

    return response()->json([
      'success' => true,
      'sid' => $sid,
      'sr' => $sr,
      's' => $s,
      'ic' => $ic
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

    } else {
      $outstanding = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_scores', [4.85, 5.00]) // Use whereBetween to filter the range
        ->get();

      $verySatisfactory = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_scores', [4.25, 4.84])
        ->get();

      $satisfactory = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_scores', [3.50, 4.24])
        ->get();

      $fair = FinalScores::where('department_id', $departmentID)
        ->whereBetween('final_scores', [2.75, 3.49])
        ->get();

      $poor = FinalScores::where('department_id', $departmentID)
        ->where('final_scores', '<', 2.75) // No need for a range for this condition
        ->get();
    }

    return response()->json([
      'success' => true,
      'oustanding' => $outstanding,
      'verySatisfactory' => $verySatisfactory,
      'satisfactory' => $satisfactory,
      'fair' => $fair,
      'poor' => $poor
    ]);
  }
}