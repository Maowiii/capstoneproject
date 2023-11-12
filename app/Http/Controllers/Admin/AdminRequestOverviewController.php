<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\EvalYear;
use App\Models\Requests;
use App\Models\Appraisals;

use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AdminRequestOverviewController extends Controller
{
    public function viewRequestOverview()
    {
        if (session()->has('account_id')) {
            $evaluationYears = EvalYear::all();
            $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;
      
        return view('admin-pages.admin_request', compact('evaluationYears', 'activeEvalYear'));
        } else {
        return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
        }
    }

    public function getUserRequests(Request $request)
    {
        $selectedYearDates = null;
        $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;
        $selectedYear = $request->input('selectedYear');
        $search = $request->input('search');

        $sy_start = null;
        $sy_end = null; 

        if ($selectedYear) {
            $parts = explode('_', $selectedYear);

            if (count($parts) >= 2) {
                $sy_start = $parts[0];
                $sy_end = $parts[1];
            }

            $selectedYearDates = EvalYear::where('sy_start', $sy_start)->first();
            $table = 'form_request_' . $selectedYear;

            // Retrieve all user requests with related data
            $userRequests = Requests::from($table)
                ->with(['appraisal.evaluator'])
                ->whereExists(function ($query) use ($search, $table) {
                $query->selectRaw(1)
                    ->from('employees')
                    ->whereRaw("$table.employee_id = employees.employee_id")
                    ->where(function ($innerQuery) use ($search) {
                    $innerQuery->orWhere('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
                })
                ->paginate(10);
        } elseif ($activeEvalYear) {
            $sy_start = $activeEvalYear->sy_start;
            $sy_end = $activeEvalYear->sy_end;

            $selectedYearDates = $activeEvalYear;

            $userRequests = Requests::with(['appraisal.evaluator'])
                ->whereHas('appraisal.employee', function ($query) use ($search) {
                    if ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                    }
                })    
              ->paginate(10);
        }else {
            return response()->json(['success' => false, 'error' => 'There is no selected nor ongoing year.']);
        }
        
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
                'requester' => $request->appraisal->evaluator->first_name . ' ' . $request->appraisal->evaluator->last_name,
                'appraisal_type' => $appraisal_type,
                'appraisee' => $request->appraisal->employee->first_name . ' ' . $request->appraisal->employee->last_name,
                'request' => $request->request,
                'locks' => $locks, 
                'date_sent' => $dateSent,
                'approver' => $request->approver ? $request->approver->first_name . ' ' . $request->approver->last_name : '-',
                'status' => $request->status,
                'action' => $request->action,
                'feedback' => $request->feedback,   
            ];
        });

        return response()->json([
            'data' => $formattedRequests,
            'last_page' => $userRequests->lastPage(),
            'current_page' => $userRequests->currentPage(),
            'links' => $userRequests->withPath('your_pagination_path')->links(), // Adjust 'your_pagination_path' accordingly
        ], 200);
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
            $account_id = session()->get('account_id');
            $approverId = Employees::where('account_id', $account_id)->pluck('employee_id')->first();

            $appraisal = Appraisals::find($appraisalID);

            if($appraisal){
                $appraisal->update(['kra_locked' => $kra]);
            
                $appraisal->update(['pr_locked' => $pr]);
            
                $appraisal->update(['eval_locked' => $eval]);
            
                $appraisal->update(['locked' => !$lock]);

                if ($appraisal->locked == 1) {
                    Log::debug('Appraisal is locked');
                    $appraisal->update(['date_submitted' => null]);

                    $signature = Signature::where('appraisal_id', $appraisalID);
                    $signature->delete();
                }
            
                $request = Requests::find($requestId);

                if ($request) {
                    $request->update([
                        'status' => 'Approved',
                        'action' => true,
                        'feedback' => $note,
                        'approver_id' => $approverId
                    ]);
                    return response()->json(['success' => true, 'message' => 'Request for unlocking the form is successfully granted.']);

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

    public function submitRequestDisapproval(Request $request)
    {
        // Validate the input
        $request->validate([
            'appraisalId' => 'required|numeric',
            'requestId' => 'required|numeric',
            'note' => 'required|string',
        ]);

        // Sanitize and use the input to build your query using prepared statements
        $requestId = $request->input('requestId');
        $appraisalID = $request->input('appraisalId');
        $note = $request->input('note');
        
        try {
            $account_id = session()->get('account_id');
            $approverId = Employees::where('account_id', $account_id)->pluck('employee_id')->first();

            $appraisal = Appraisals::find($appraisalID);

            if ($appraisal) {
                $request = Requests::find($requestId);

                if ($request) {
                    $request->update([
                        'status' => 'Disapproved',
                        'action' => false,
                        'feedback' => $note,
                        'approver_id' => $approverId
                    ]);
                    return response()->json(['success' => true, 'message' => 'Request for unlocking the form is successfully not granted.']);
                }
            } else{
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
