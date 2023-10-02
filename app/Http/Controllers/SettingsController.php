<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\Accounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
  public function displaySettings()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    return view('settings.settings');
  }

  public function displayEmployeeInfo()
  {

    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
    $account_id = session()->get('account_id');
    $employee = Employees::where('account_id', $account_id)->first();
    $accounts = Accounts::where('account_id', $account_id)->first();

    // Initialize variables for first name, last name, and Adamson Mail
    $firstName = null;
    $lastName = null;
    $adamsonMail = null;

    // Check if the user is authenticated and has an associated employee record
    if ($accounts && $accounts->employee) {
      // Access the Employee model associated with the user's account
      $employee = $accounts->employee;
      // Retrieve the first and last name from the Employee model
      $firstName = $employee->first_name;
      $lastName = $employee->last_name;
      $adamsonMail = $accounts->email;
    }

    // Return the employee information as JSON
    return response()->json([
      'success' => true,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'email' => $adamsonMail,
    ]);
  }

  public function changePassword(Request $request)
  {

    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $validator = Validator::make($request->all(), [
      'current_password' => 'required',
      'new_password' => 'required|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
      'confirm_password' => 'required|same:new_password'
    ], [
      'password.required' => 'Please enter your current password.',
      'new_password.required' => 'Please enter your new password.',
      'new_password.min' => 'Password must have a minimum length of 8.',
      'new_password.max' => 'Password is limited to 20 characters.',
      'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
      'confirm_password.same' => 'The password confirmation does not match.'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $account_id = session()->get('account_id');
    $user = Accounts::find($account_id)->first();

    if ($request->current_password == $user->default_password || Hash::check($request->current_password, $user->password)) {
      $newPassword = $request->new_password;
      $hashedPassword = Hash::make($newPassword);

      $user->password = $hashedPassword;
      $user->save();

      return redirect()->back()->with('success', 'Password changed successfully.');
    } else {
      return redirect()->back()->with('error', 'Current password is incorrect. Password change failed.');
    }
  }
}