<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\EvalYear;
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
      $parts = explode('_', $selectedYear);

      if (count($parts) >= 2) {
        $sy_start = $parts[0];
        $sy_end = $parts[1];
      }

      Log::debug('No Selected Year');
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
      Log::debug('No Selected Year');
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

}