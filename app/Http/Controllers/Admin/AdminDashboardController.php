<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function displayAdminDashboard()
    {
        return view('dashboard.admin_dashboard');
    }
}
