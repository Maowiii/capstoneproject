<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AdminRequestOverviewController extends Controller
{
    public function viewRequestOverview()
    {
        if (!session()->has('account_id')) {
            return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
        }
        return view('admin-pages.admin_request');
    }
}
