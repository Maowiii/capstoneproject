<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\NewPasswordEmail;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\EvalYear;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Imports\ImportEmployeeSample;
use App\Imports\ImportEmployee;

class SuperAdminEmployeesController extends Controller
{
    public function displayEmployeeTable()
  {
    if (session()->has('account_id')) {
      $departments = Departments::all();
      return view('superadmin-pages.super-admin-accounts')->with('departments', $departments);
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
    })->paginate(10);

    // Log::debug($accounts);

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
      'employee_number' => 'required|max:11|unique:employees,employee_number',
      'first_name' => 'required',
      'last_name' => 'required',
      'type' => 'required|in:AD,IS,PE,CE,SA',
      'department' => in_array($request->input('type'), ['AD', 'SA']) ? 'nullable' : 'required',
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
      'department.required' => 'Please choose a department unless user level is "AD".',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput()->with('showAddUserModal', true);
    } else {

      try{
        $randomPassword = Str::random(8);
        $account = Accounts::create([
          'email' => $request->input('email'),
          'default_password' => $randomPassword,
          'type' => $request->input('type'),
          'first_login' => 'true'
        ]);

        $account_id = $account->account_id;

        $departmentID = $request->input('department');

        $employee = Employees::create([
          'account_id' => $account_id,
          'employee_number' => $request->input('employee_number'),
          'first_name' => $request->input('first_name'),
          'last_name' => $request->input('last_name'),
          'department_id' => $departmentID
        ]);

        if(!in_array($account->type, ['AD', 'IS', 'CE', 'SA'])){
            $isAccount = Accounts::where('type', 'IS')
            ->whereHas('employee', function ($query) use ($departmentID) {
                $query->where('department_id', $departmentID);
            })->first();

            $immediateSuperior = $isAccount->employee->employee_id;

            Employees::where('account_id', $employee->account_id)
                ->update([
                    'immediate_superior_id' => $immediateSuperior,
                ]);
        }

        $departments = Departments::all();
        return view('superadmin-pages.super-admin-accounts')->with('departments', $departments)->with('success', 'Accounts successfully created.');
      }catch (\Exception $e) {
        Log::error($e);
        $departments = Departments::all();
        return view('superadmin-pages.super-admin-accounts')->with('departments', $departments);  
      }

      
    }
  }

  public function importEmployee(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    // Validate the uploaded file
    $validator = Validator::make($request->all(), [
      'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // Get the uploaded file
    $file = $request->file('file');

    // Process the Excel file
    $import = new ImportEmployee;
    $import->import($file);
    $successCount = $import->getSuccessCount();

    // Fetch all departments (you can use this as needed)
    $departments = Departments::all();

    if ($import->failures()->isNotEmpty()) {
      // Filter out rows with all null values
      $filteredFailures = $import->failures()->reject(function ($failure) {
        // Check if all values in the row are null
        return collect($failure->values())->every(function ($value) {
          return $value === null || trim($value) === '';
        });
      });

      // Check if there are still failures after filtering
      if ($filteredFailures->isNotEmpty()) {
        return view('superadmin-pages.super-admin-accounts')->with([
          'departments' => $departments,
          'failures' => $filteredFailures,
        ]);
      }
    }

    if ($successCount === 1) {
      $status = $successCount . ' employee was successfully added.';
    } elseif ($successCount >= 1) {
      $status = $successCount . ' employees were successfully added.';
    } else {
      $status = null;
    }

    // Log info message
    Log::info('All departments fetched.');

    return view('superadmin-pages.super-admin-accounts')->with([
      'departments' => $departments,
      'success' => $status,
    ]);
  }

  public function importEmployeeSample(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    try {
      // Validate the uploaded file
      $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
      ]);

      // Get the uploaded file
      $file = $request->file('file')->store('uploadFiles');

      // Process the Excel file
      $import = new ImportEmployeeSample;
      $import->import($file);

      $failures = $import->failures();

      foreach ($failures as $failure) {
        // Access the row number
        $row = $failure->row();

        // Access the attribute that failed
        $attribute = $failure->attribute();

        // Access the errors for this failure
        $errors = $failure->errors();

        // You can then do something with this information, such as logging or displaying to the user
        dd("Row: $row, Attribute: $attribute, Errors: " . json_encode($errors));
      }


      // Fetch all departments (you can use this as needed)
      $departments = Departments::all();

      // Log info message
      Log::info('All departments fetched.');

      return view('superadmin-pages.super-admin-accounts')->with('departments', $departments);
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
      $departments = Departments::all();
      $failures = $e->failures();

      foreach ($failures as $failure) {
        $failure->row(); // row that went wrong
        $failure->attribute(); // either heading key (if using heading row concern) or column index
        $failure->errors(); // Actual error messages from Laravel validator
        $failure->values(); // The values of the row that has failed.
      }

      // Log error message
      Log::error('Error importing Excel file: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      return redirect()->back()->withErrors([$e->getMessage()]);
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
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    $employeeId = $request->input('employeeId');

    $employee = Employees::find($employeeId);

    if (!$employee) {
      return response()->json(['success' => false, 'error' => 'Employee not found']);
    }

    $employee->first_name = $request->input('firstName');
    $employee->last_name = $request->input('lastName');
    $employee->department_id = $request->input('department');
    $employee->save();

    $account = Accounts::where('account_id', $employee->account_id)->first();

    if (!$account) {
      return response()->json(['success' => false, 'error' => 'Account not found']);
    }

    $account->email = $request->input('email');
    $account->type = $request->input('type');
    $account->save();

    return response()->json(['success' => true]);
  }
    
}
