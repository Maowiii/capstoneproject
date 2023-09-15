<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\EvalYear;
use Illuminate\Http\Request;

class ISAppraisalsOverviewController extends Controller
{
  public function displayISAppraisalsOverview()
  {
    $account_id = session()->get('account_id');
    $user = Employees::where('account_id', $account_id)->first();

    $department_id = $user->department_id;
    $appraisals = Employees::where('department_id', $department_id)->get();

    $activeEvalYear = EvalYear::where('status', 'active')->first();
    return view('is-pages.is_appraisals_overview')
      ->with('appraisals', $appraisals)
      ->with('activeEvalYear', $activeEvalYear);
  }

  public function getData(Request $request)
  {
    $account_id = session()->get('account_id');
    $user = Employees::where('account_id', $account_id)->first();

    $department_id = $user->department_id;
    $appraisee = Employees::where('department_id', $department_id)
      ->whereNotIn('account_id', [$account_id])
      ->get();

    $appraisals = Appraisals::whereHas('employee', function ($query) use ($department_id) {
      $query->where('department_id', $department_id);
    })
      ->where('employee_id', '<>', $user->id)
      ->with('employee', 'evaluator') // Load the related employee and evaluator information
      ->get();

    $data = [
      'success' => true,
      'appraisee' => $appraisee,
      'appraisals' => $appraisals,
      'is' => $user
    ];
    return response()->json($data);
  }

  public function getEmployees(Request $request)
  {
    $accounts = Accounts::whereIn('type', ['PE', 'IS', 'CE'])->get();

    $employeeIds = $accounts->pluck('account_id');

    $employees = Employees::whereIn('account_id', $employeeIds)->get();

    // Retrieve department names
    $departmentIds = $employees->pluck('department_id');
    $departments = Departments::whereIn('department_id', $departmentIds)->pluck('department_name', 'department_id');

    foreach ($employees as $employee) {
      $employee->department_name = $departments[$employee->department_id] ?? 'Unknown Department';
    }

    $data = [
      'success' => true,
      'employees' => $employees
    ];
    return response()->json($data);
  }
  
  public function assignInternalCustomer(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'internal_customer_id' => 'required|exists:employees,id', // Assuming internal customers are also employees
    ]);

    try {
        // Find the employee by ID
        $employee = Employees::findOrFail($validatedData['employee_id']);

        // Update the employee's internal_customer_id
        $employee->internal_customer_id = $validatedData['internal_customer_id'];
        $employee->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Internal Customer assigned successfully.');
    } catch (\Exception $e) {
        // Handle any errors, such as database errors or invalid data
        return redirect()->back()->with('error', 'Failed to assign Internal Customer. Please try again.');
    }
}

}