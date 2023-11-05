<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requests;
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

    public function getUserRequests()
    {
        // Retrieve all user requests with related data
        $userRequests = Requests::with(['appraisal.evaluator'])->get();
        
        // Map the data for the response
        $formattedRequests = $userRequests->map(function ($request) {
        
            $timestamp = $request['created_at'];
            $dateSent = date('F j, Y H:i:s', strtotime($timestamp));
            return [
                'name' => $request->appraisal->evaluator->first_name . ' ' . $request->appraisal->evaluator->last_name,
                'request' => $request->request,
                'date_sent' => $dateSent, 
                'status' => $request->status,
                'action' => $request->action,   
            ];
        });

        return response()->json($formattedRequests);
    }   
}
