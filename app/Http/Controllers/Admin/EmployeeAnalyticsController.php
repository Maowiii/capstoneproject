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

      return view('admin-pages.admin_employee_analytics');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

}
