<?php

namespace App\Http\Controllers\ContractualEmployee;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use Illuminate\Http\Request;

class CEDashboardController extends Controller
{
  public function displayCEDashboard()
  {
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();
    $department_id = $user->employee->department_id;

    $immediate_superiors = Accounts::where('type', 'IS')->with('employee')->whereHas('employee', function ($query) use ($department_id) {
      $query->where('department_id', $department_id);
    })->get();

    return view('ce-pages.ce_dashboard')->with('IS', $immediate_superiors);
  }

}