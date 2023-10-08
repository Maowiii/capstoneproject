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
      ->where('employee_id', '<>', $user->account_id)
      ->whereHas('account', function ($query) {
        $query->where('type', 'PE');
      })
      ->paginate(10);

    // Handle the rest of your logic here, using $employee
    $activeYear = EvalYear::where('status', 'active')->first();

    if ($activeYear) {
      $evaluationTypes = ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'];

      foreach ($appraisee as $appraiseeItem) {
        Log::info($appraiseeItem);
        // Check if the employee has existing appraisal records
        $existingAppraisals = Appraisals::where('employee_id', $appraiseeItem->employee_id)->count();
        Log::info($existingAppraisals);

        // If no existing appraisal records, create new ones
        if ($existingAppraisals == 0) {
          foreach ($evaluationTypes as $evaluationType) {
            $evaluatorId = null;

            if ($evaluationType === 'self evaluation') {
              $evaluatorId = $appraiseeItem->employee_id;
            } elseif ($evaluationType === 'is evaluation') {
              $departmentId = $appraiseeItem->department_id;
              $isAccount = Accounts::where('type', 'IS')
                ->whereHas('employee', function ($query) use ($departmentId) {
                  $query->where('department_id', $departmentId);
                })->first();

              if ($isAccount) {
                $evaluatorId = $isAccount->employee->employee_id;
              }
            }

            Appraisals::create([
              'evaluation_type' => $evaluationType,
              'employee_id' => $appraiseeItem->employee_id,
              'evaluator_id' => $evaluatorId,
              'department_id' => $appraiseeItem->department_id,
              // Corrected this line
            ]);
          }
        }
      }
    }

    $appraisals = Appraisals::where('department_id', $user->department_id)
      ->where('employee_id', '<>', $user->account_id)
      ->with('employee', 'evaluator')
      ->paginate(40); // Specify the number of items per page

    $data = [
      'success' => true,
      'appraisee' => $appraisee,
      'appraisals' => $appraisals,
      // Include paginated appraisals data
      'is' => $user,
    ];

    return response()->json($data);
  }


  public function getEmployees(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    $excludedEmployeeId = $request->input('excludedEmployeeId');

    $accounts = Accounts::whereIn('type', ['PE', 'CE'])->get();

    $employeeIds = $accounts->pluck('account_id');

    $employees1 = Employees::whereIn('account_id', $employeeIds)->get();
    Log::info($employees1);
    $employees = Employees::whereIn('account_id', $employeeIds)->paginate(10);
    Log::info($employees);

    // Retrieve department names
    $departmentIds = $employees->pluck('department_id');
    $departments = Departments::whereIn('department_id', $departmentIds)->pluck('department_name', 'department_id');

    foreach ($employees as $employee) {
      $employee->department_name = $departments[$employee->department_id] ?? 'Unknown Department';
    }

    $evaluatorId = Appraisals::where('employee_id', $excludedEmployeeId)
      ->where('evaluation_type', 'like', 'internal customer%')
      ->whereNotNull('evaluator_id')
      ->pluck('evaluator_id')
      ->first();

    $data = [
      'success' => true,
      'employees' => $employees,
      'evaluatorId' => $evaluatorId
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
    try {
      // Validate the request data
      $validatedData = $request->validate([
        'employee_id' => 'required|array|between:1,2',
        // Ensure exactly 2 internal customers are selected
        'appraisalId' => 'required|integer',
      ]);

      // Extract validated data
      $employeeIds = $validatedData['employee_id'];
      $appraisalIdIC1 = (int) $validatedData['appraisalId'];
      $appraisalIdIC2 = $appraisalIdIC1 + 1; // Calculate ID for the second internal customer

      // Initialize an array to store updated appraisals
      $updatedAppraisals = [];

      foreach ($employeeIds as $index => $employeeId) {
        $employee = Employees::where('account_id', $employeeId)->first();

        // Try to find an existing appraisal for this employee and appraisal ID
        $existingAppraisal = Appraisals::where('appraisal_id', ($index === 0) ? $appraisalIdIC1 : $appraisalIdIC2)
          ->first();

        if ($existingAppraisal !== null) {
          // Update the existing appraisal record
          $existingAppraisal->evaluator_id = $employeeId;
          $existingAppraisal->save();

          $updatedAppraisals[] = $existingAppraisal;
        } else {
          // Create a new appraisal record
          $appraisal = new Appraisals();
          $appraisal->employee_id = $employeeId;
          $appraisal->evaluator_id = $employeeId;
          $appraisal->department_id = $employee->department_id; // Set the department_id
          $appraisal->appraisal_id = ($index === 0) ? $appraisalIdIC1 : $appraisalIdIC2; // Set the appraisal_id
          $appraisal->evaluation_type = ($index === 0) ? 'internal customer 1' : 'internal customer 2';
          $appraisal->save();

          $updatedAppraisals[] = $appraisal;
        }
      }

      // Return a success response with updated appraisals
      $data = [
        'success' => true,
        'updated_appraisals' => $updatedAppraisals,
      ];
      return response()->json($data);
    } catch (\Exception $e) {
      // Handle any errors, such as database errors or invalid data
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      $data = [
        'error' => 'Failed to assign Internal Customers. ' . $e->getMessage(),
      ];
      return response()->json($data, 400);
    }
  }


}