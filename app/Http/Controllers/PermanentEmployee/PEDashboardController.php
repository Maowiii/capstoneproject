<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\Accounts;
use Illuminate\Http\Request;

class PEDashboardController extends Controller
{
  public function displayPEDashboard()
  {
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();
    $department_id = $user->employee->department_id;
    $first_login = $user->first_login;


    $immediate_superiors = Accounts::where('type', 'IS')->with('employee')->whereHas('employee', function ($query) use ($department_id) {
      $query->where('department_id', $department_id);
    })->get();

    return view('pe-pages.pe_dashboard')
      ->with('IS', $immediate_superiors)
      ->with('first_login', $first_login);
  }

  public function submitPEFirstLogin(Request $request)
  {
    $job_title = $request->job_title;
    $request->session()->put('title', $job_title);
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();

    $user->employee->update([
      'job_title' => $job_title,
    ]);

    $user->update([
      'first_login' => 'false'
    ]);

    return response()->json(['success' => true]);
  }
}