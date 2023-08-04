<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ISDashboardController extends Controller
{
    public function displayISDashboard() {
        return view('dashboard.is_dashboard');
    }
}