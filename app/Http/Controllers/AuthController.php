<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationCode;
use App\Models\Employees;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
  public function displayLogin()
  {
    return view('auth.login');
  }

  public function displayTwoFactorAuth()
  {
    return view('auth.two_factor_authentication');
  }

  public function displayResetPassword()
  {
    return view('auth.reset_password_verifyEmail');
  }

  public function displayResetPassword2()
  {
    return view('auth.reset_password_verifyCode');
  }

  public function displayResetPassword3()
  {
    return view('auth.reset_password_changePassword');
  }
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|ends_with:adamson.edu.ph',
      'password' => 'required|max:20',
    ], [
      'email.required' => 'Please enter your Adamson email address.',
      'email.ends_with' => 'Please enter your Adamson email address.',
      'email.email' => 'Please enter a valid email address.',
      'password.required' => 'Please enter your password.',
      'password.min' => 'Password must be at least 8 characters long.',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $accounts = Accounts::where('email', '=', $request->email)->first();

    if ($accounts) {
      if ($accounts->status == "active") {
        if ($request->password == $accounts->default_password || Hash::check($request->password, $accounts->password)) {

          $request->session()->put('account_id', $accounts->account_id);
          $request->session()->put('email', $request->email);
          $request->session()->put('user_level', $accounts->type);

          $account_id = session()->get('account_id');
          $employee = Employees::where('account_id', $account_id)->first();
          $full_name = $employee->first_name . ' ' . $employee->last_name;
          session()->put('full_name', $full_name);

          if ($accounts->type == 'AD') { // For Demo Purposes only
            session()->put('full_name', 'Administrator'); // wala pang account
            session()->put('title', 'Administrator');
            session()->put('user_level', 'AD');
            return redirect()->route('viewAdminDashboard');
          } elseif ($accounts->type == 'SA'){
            session()->put('full_name', 'Administrator'); // wala pang account
            session()->put('title', 'Administrator');
            session()->put('user_level', 'SA');
            return redirect()->route('viewSADashboard');
          } else {
            $code = rand(1000, 9999);
            $accounts->verification_code = $code;
            $request->session()->put('verification_code', $code);
            Mail::to($request->email)->send(new VerificationCode($code));
            $accounts->save();
            return redirect()->route('viewTwoFactorAuth');
          }
        } else {
          return back()->with('fail', 'Incorrect Password.');
        }
      } else {
        return back()->with('fail', 'User deactivated. Please contact your HR adminstrator.');
      }
    } else {
      return back()->with('fail', 'User not found. Please contact your HR adminstrator.');
    }
  }

  public function verifyTwoFactorAuth(Request $request)
  {
    $request->validate([
      'code1' => 'required|numeric',
      'code2' => 'required|numeric',
      'code3' => 'required|numeric',
      'code4' => 'required|numeric',
    ]);

    $code = $request->code1 . $request->code2 . $request->code3 . $request->code4;
    $email = session('email');
    $accounts = Accounts::where('email', '=', $email)->first();
    $account_id = session()->get('account_id');
    $employee = Employees::where('account_id', $account_id)->first();

    if ($accounts->verification_code == $code) {
      if ($accounts->type == 'IS') {
        $title = $employee->position;
        $request->session()->put('title', $title);
        return redirect()->route('viewISDashboard');
      } elseif ($accounts->type == 'PE') {
        $title = $employee->job_title;
        $request->session()->put('title', $title);
        return redirect()->route('viewPEDashboard');
      } else {
        $title = $employee->job_title;
        $request->session()->put('title', $title);
        return redirect()->route('viewCEDashboard');
      }
    } else {
      return back()->with('fail', 'Code is incorrect. Please try again.');
    }
  }

  public function step1_VerifyEmail(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|ends_with:adamson.edu.ph'
    ], [
      'email.required' => 'Please enter your Adamson email address.',
      'email.ends_with' => 'Please enter your Adamson email address.',
      'email.email' => 'Please enter a valid email address.'
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $accounts = Accounts::where('email', '=', $request->email)->first();

    if ($accounts) {
      if ($accounts->status == "active") {
        $code = rand(1000, 9999);
        $accounts->verification_code = $code;
        $accounts->save();
        $request->session()->put('email', $request->email);
        $request->session()->put('verification_code', $code);
        // SEND CODE BEFORE PROCEEDING:
        Mail::to($request->email)->send(new VerificationCode($code));
        return redirect()->route('viewResetPassword2');
      } else {
        return back()->with('fail', 'User deactivated. Please contact your HR adminstrator.');
      }
    } else {
      return back()->with('fail', 'User not found. Please contact your HR adminstrator.');
    }
  }

  public function step2_VerifyCode(Request $request)
  {
    $request->validate([
      'code1' => 'required|numeric',
      'code2' => 'required|numeric',
      'code3' => 'required|numeric',
      'code4' => 'required|numeric',
    ]);

    $code = $request->code1 . $request->code2 . $request->code3 . $request->code4;

    $email = session('email');

    $accounts = Accounts::where('email', '=', $email)->first();

    if ($accounts->verification_code == $code) {
      return redirect()->route('viewResetPassword3');
    } else {
      return back()->with('fail', 'Code is incorrect. Please try again.');
    }
  }

  public function step3_ResetPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
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

    if ($request->password == $request->confirm_password) {
      $email = session('email');
      $accounts = Accounts::where('email', '=', $email)->first();
      $hashedPassword = Hash::make($request->password);
      $accounts->password = $hashedPassword;
      $accounts->save();
      return redirect()->route('viewLogin')->with('password-reset-success', 'Your password has been reset.');
    }
  }

  public function sendCode()
  {
    $email = session('email');
    $code = rand(1000, 9999);
    session()->put('verification_code', $code);

    $accounts = Accounts::where('email', '=', $email)->first();
    $accounts->verification_code = $code;
    $accounts->save();
    return back()->with('resend_code_success', 'Verification code has been sent again.');
  }

  public function logout()
  {
    session()->flush();

    return redirect()->route('viewLogin');
  }
}