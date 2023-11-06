<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Employees;
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
}
