<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\Appraisals;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeAnalyticsController extends Controller
{
  public function displayEmployeeAnalytics()
  {
    if (session()->has('account_id')) {

      return view('admin-pages.admin_departmental_analytics');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }
  public function loadAvg(Request $request)
  {
    $selectedYear = $request->input('selectedYear');
    $employeeID = $request->input('employeeID');

    if ($selectedYear) {

      $avgTotalScore = FinalScores::where('department_id', $employeeID)
        ->avg('final_score');
      $avgTotalScore = round($avgTotalScore, 2);
    } else {
      if (AppraisalAnswers::tableExists() && FormQuestions::tableExists()) {
        $avgTotalScore = FinalScores::where('employee_id', $employeeID)->avg('final_score');
        $avgTotalScore = round($avgTotalScore, 2);
      } else {
        return response()->json(['success' => false]);
      }
    }
    return response()->json([
      'success' => true,
      'avgTotalScore' => $avgTotalScore
    ]);
  }
}
