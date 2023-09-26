<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
  public function displaySettings()
  {
    return view('settings.settings');
  }

  public function changePassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
      'new_password' => 'required|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
      'confirm_password' => 'required|same:password'
    ], [
      'password.required' => 'Please enter your password.',
      'password.min' => 'Password must have a minimum length of 8.',
      'password.max' => 'Password is limited to 20 characters.',
      'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
      'confirm_password.same' => 'The password confirmation does not match.'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  }
}