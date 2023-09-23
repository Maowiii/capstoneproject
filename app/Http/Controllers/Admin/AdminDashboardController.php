<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
  public function displayAdminDashboard()
  {
    if (session()->has('account_id')) {
      return view('admin-pages.admin_dashboard');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }
}