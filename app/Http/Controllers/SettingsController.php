<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
  public function displaySettings()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    return view('settings.settings');
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