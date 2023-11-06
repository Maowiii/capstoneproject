<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Appraisals;

use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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

            $appraisal_type = $request->appraisal->evaluation_type;
            $appraisal_type = ucwords($appraisal_type);
            
            $locks = [];

            $locks['kra'] = $request->appraisal->kra_locked == 1;
            $locks['pr'] = $request->appraisal->pr_locked == 1;
            $locks['eval'] = $request->appraisal->eval_locked == 1;
            $locks['lock'] = $request->appraisal->locked !== 1;

            return [
                'request_id' => $request->request_id,
                'appraisal_id' => $request->appraisal_id,
                'name' => $request->appraisal->evaluator->first_name . ' ' . $request->appraisal->evaluator->last_name,
                'appraisal_type' => $appraisal_type,
                'request' => $request->request,
                'date_sent' => $dateSent, 
                'status' => $request->status,
                'action' => $request->action,   
            ];
        });

        return response()->json($formattedRequests, 200);
    }
    
    public function submitRequestApproval(Request $request)
    {
        // Validate the input
        $request->validate([
            'appraisalId' => 'required|numeric',
            'requestId' => 'required|numeric',
            'kra' => 'boolean',
            'pr' => 'boolean',
            'eval' => 'boolean',
            'lock' => 'boolean',
            'note' => 'nullable|string',
        ]);

        // Sanitize and use the input to build your query using prepared statements
        $requestId = $request->input('requestId');
        $appraisalID = $request->input('appraisalId');
        $kra = $request->input('kra');
        $pr = $request->input('pr');
        $eval = $request->input('eval');
        $lock = $request->input('lock');
        $note = $request->input('note');
        
        try {
            $appraisal = Appraisals::find($appraisalID);

            if($appraisal){
                if($kra){
                    $locked = $appraisal->kra_locked;
    
                    $appraisal->update(['kra_locked' => !$locked]);
                }
                
                if ($pr){
                    $locked = $appraisal->pr_locked;
    
                    $appraisal->update(['pr_locked' => !$locked]);
                }
                
                if ($eval){
                    $locked = $appraisal->eval_locked;
    
                    $appraisal->update(['eval_locked' => !$locked]);
                }
                
                if ($lock){    
                    $locked = $appraisal->locked;
    
                    $appraisal->update(['locked' => !$locked]);
    
                    if ($appraisal->locked == 1) {
                        Log::debug('Appraisal is locked');
                        $appraisal->update(['date_submitted' => null]);
    
                        $signature = Signature::where('appraisal_id', $appraisalID);
                        $signature->delete();
                    }
                }

                $request = Requests::find($requestId);

                if ($request) {
                    $request->update([
                        'status' => 'Approved',
                        'action' => "true",
                        'feedback' => $note
                    ]);
                    return response()->json(['success' => true, 'message' => 'Request for Approval is successful.']);

                }else{
                    return response()->json(['success' => false, 'message' => 'Error updating request.']);
                }
            }else{
                return response()->json(['success' => false, 'message' => 'Error updating appraisal form.']);
            }
        } catch (\Exception $e) {
            Log::error('Exception Message: ' . $e->getMessage());
            Log::error('Exception Line: ' . $e->getLine());
            Log::error('Exception Stack Trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'An error occurred while granting approval for the request. Please try again later.']);
        }
    }
}
