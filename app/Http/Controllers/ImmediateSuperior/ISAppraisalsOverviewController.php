<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\EvalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ISAppraisalsOverviewController extends Controller
{
  public function displayISAppraisalsOverview()
  {
    if (session()->has('account_id')) {
      $account_id = session()->get('account_id');
      $user = Employees::where('account_id', $account_id)->first();

      $department_id = $user->department_id;
      $appraisals = Employees::where('department_id', $department_id)->get();

      $activeEvalYear = EvalYear::where('status', 'active')->first();
      return view('is-pages.is_appraisals_overview')
        ->with('appraisals', $appraisals)
        ->with('activeEvalYear', $activeEvalYear);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getData(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

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
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
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

  public function updateInternalCustomer(Request $request)
  {
    $appraisalId = $request->input('appraisal_id');
    $evaluatorId = $request->input('evaluator_id');

    // Update the evaluator_id in the Appraisals table
    Appraisals::where('appraisal_id', $appraisalId)
      ->update(['evaluator_id' => $evaluatorId]);

    // Return a response indicating success
    return response()->json(['success' => true]);
  }

  public function assignInternalCustomer(Request $request)
  {
    // Validate the request data
    $validatedData = $request->validate([
      'employee_id' => 'required',
      'appraisalId' => 'required',
    ]);

    try {
      $employeeId = (int) $validatedData['employee_id'][0];
      $appraisalId = (int) $validatedData['appraisalId'];

      $firstAppraisal = Appraisals::where('appraisal_id', $appraisalId)->first();

      if ($firstAppraisal !== null) {
        $firstAppraisal->update(['evaluator_id' => $employeeId]);
      }

      // Return a success response
      $data = [
        'success' => true,
      ];
      return response()->json($data);

    } catch (\Exception $e) {

      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());
      // Handle any errors, such as database errors or invalid data
      $data = [
        'error' => 'Failed to assign Internal Customer. Please try again.',
      ];
      return response()->json($data, 400);
    }
  }
}