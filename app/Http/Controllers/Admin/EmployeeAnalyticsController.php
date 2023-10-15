<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvalYear;
use Illuminate\Http\Request;

class EmployeeAnalyticsController extends Controller
{
  public function displayEmployeeAnalytics()
  {
    if (session()->has('account_id')) {
      $evaluationYears = EvalYear::all();
      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      return view('admin-pages.employee_analytics', compact('evaluationYears', 'activeEvalYear'));
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }
}
