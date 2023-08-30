<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewPasswordEmail;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\EvalYear;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
  public function displayEmployeeTable()
  {
    $departments = Departments::all();
    return view('admin-pages.employee_table')->with('departments', $departments);
  }

  public function updateStatus(Request $request)
  {
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
    $accounts = Accounts::with('employee.department')->get();

    $data = [
      'success' => true,
      'accounts' => $accounts
    ];

    return response()->json($data);
  }

  public function addEmployee(Request $request)
  {
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

      $departments = Departments::all();
      return view('admin-pages.employee_table')->with('departments', $departments);
    }
  }

  public function employeeResetPassword(Request $request)
  {
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
}