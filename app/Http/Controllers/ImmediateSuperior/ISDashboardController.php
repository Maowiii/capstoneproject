<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use Illuminate\Http\Request;

class ISDashboardController extends Controller
{
  public function displayISDashboard()
  {
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();

    $first_login = $user->first_login;

    return view('is-pages.is_dashboard', ['first_login' => $first_login]);
  }

  public function submitISPosition(Request $request)
  {
    $position = $request->position;
    $request->session()->put('title', $position);
    $account_id = session()->get('account_id');
    $user = Accounts::where('account_id', $account_id)->with('employee')->first();

    $user->employee->update([
      'position' => $position,
    ]);

    $user->update([
      'first_login' => 'false'
    ]);

    return response()->json(['success' => true]);
  }

}