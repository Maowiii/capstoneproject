<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewPasswordEmail;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\EvalYear;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
  public function displayEmployeeTable()
  {
    if (session()->has('account_id')) {
      $departments = Departments::all();
      return view('admin-pages.employee_table')->with('departments', $departments);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function updateStatus(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $accountId = $request->input('account_id');
    $action = $request->input('action');

    $account = Accounts::find($accountId);
    if ($account) {
      if ($action === 'activate') {
        $account->status = 'active';
      } elseif ($action === 'deactivate') {
        $account->status = 'deactivated';
      }

      $account->save();
      return response()->json(['success' => true]);
    }
    return response()->json(['success' => false, 'error' => 'Account not found.']);
  }

  public function getData(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $search = $request->input('search');

    $accounts = Accounts::with([
      'employee.department'
    ])->whereHas('employee', function ($query) use ($search) {
      $query->where(function ($subQuery) use ($search) {
        $subQuery->where('first_name', 'like', '%' . $search . '%')
          ->orWhere('last_name', 'like', '%' . $search . '%')
          ->orWhere('employee_number', 'like', '%' . $search . '%');
      });
    })->get();

    Log::debug($accounts);

    $data = [
      'success' => true,
      'accounts' => $accounts
    ];

    return response()->json($data);
  }

  public function addEmployee(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $validator = Validator::make($request->all(), [
      'email' => 'required|email|ends_with:adamson.edu.ph|unique:accounts,email',
      'employee_number' => 'required|max:9|unique:employees,employee_number',
      'first_name' => 'required',
      'last_name' => 'required',
      'type' => 'required',
      'department' => 'required'
    ], [
      'email.required' => 'Please enter an Adamson email address.',
      'email.ends_with' => 'Please enter an Adamson email address.',
      'email.email' => 'Please enter a valid email address.',
      'email.unique' => 'The email is already in use',
      'employee_number.required' => 'Please enter an employee number.',
      'employee_number.unique' => 'The employee number is already in use.',
      'employee_number.max' => 'Please enter a valid employee number.',
      'first_name.required' => 'Please enter the employee\'s first name.',
      'last_name.required' => 'Please enter the employee\'s last name.',
      'type.required' => 'Please choose a user level.',
      'department.required' => 'Please choose a department.'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput()->with('showAddUserModal', true);
    } else {
      $randomPassword = Str::random(8);
      $account = Accounts::create([
        'email' => $request->input('email'),
        'default_password' => $randomPassword,
        'type' => $request->input('type'),
        'first_login' => 'true'
      ]);

      $account_id = $account->account_id;

      $employee = Employees::create([
        'account_id' => $account_id,
        'employee_number' => $request->input('employee_number'),
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'department_id' => $request->input('department')
      ]);

      $activeYear = EvalYear::where('status', 'active')->first();
      if ($activeYear && !in_array($account->type, ['AD', 'CE'])) {
        $evaluationTypes = ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'];
        foreach ($evaluationTypes as $evaluationType) {
          $evaluatorId = null;

          if ($evaluationType === 'self evaluation') {
            $evaluatorId = $employee->employee_id;
          } elseif ($evaluationType === 'is evaluation') {
            $departmentId = $employee->department_id;
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
            'employee_id' => $employee->employee_id,
            'evaluator_id' => $evaluatorId,
          ]);
        }
      }

      $departments = Departments::all();
      return view('admin-pages.employee_table')->with('departments', $departments);
    }
  }

  public function employeeResetPassword(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    try {
      $accountId = $request->input('account_id');

      $employee = Accounts::findOrFail($accountId);

      $newPassword = Str::random(8);
      $employee->update([
        'default_password' => $newPassword
      ]);

      Mail::to($employee->email)->send(new NewPasswordEmail($newPassword));

      return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Password reset failed']);
    }
  }

  public function editEmployee(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $employeeId = $request->input('employeeId');
    $employee = Employees::with('account')->find($employeeId);

    if (!$employee) {
      return response()->json(['success' => false, 'error' => 'Employee not found']);
    }

    return response()->json(['success' => true, 'employee' => $employee]);
  }

  public function saveEmployee(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
    $employeeId = $request->input('employeeId');
    $account = Accounts::where('account_id', $employeeId)->first();
    $employee = Employees::find($employeeId)->first();

    if (!$employee) {
      return response()->json(['success' => false, 'error' => 'User not found']);
    } else if (!$account) {
      return response()->json(['success' => false, 'error' => 'User not found']);
    }

    $account->email = $request->input('email');
    $account->type = $request->input('type');
    $employee->employee_number = $request->input('employeeNumber');
    $employee->first_name = $request->input('firstName');
    $employee->last_name = $request->input('lastName');
    $employee->department_id = $request->input('department');

    $employee->save();
    $account->save();

    return response()->json(['success' => true]);
  }
}