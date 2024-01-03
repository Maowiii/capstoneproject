<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\EvalYear;
use App\Models\Requests;
use App\Models\Appraisals;

use App\Models\Signature;
use DB;
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

        // Requests::setSelectedYear($selectedYear);
        DB::enableQueryLog();

        if (!$selectedYear && !$activeEvalYear) {
            return response()->json(['success' => false, 'error' => 'No selected nor ongoing year.']);
        }
        
        $selectedYear = $request->input('selectedYear') ?? $activeEvalYear->sy_start . '_' . $activeEvalYear->sy_end;
        $formRequestTable = 'form_request_' . $selectedYear;
        $appraisalTable = 'appraisals_' . $selectedYear;
        // dd($formRequestTable.' '.$appraisalTable);

        $userRequests = Requests::from($formRequestTable)
            ->join($appraisalTable, "{$formRequestTable}.appraisal_id", '=', "{$appraisalTable}.appraisal_id")
            ->leftJoin('employees as evaluator', "{$appraisalTable}.evaluator_id", '=', 'evaluator.employee_id')
            ->leftJoin('employees as appraisee', "{$appraisalTable}.employee_id", '=', 'appraisee.employee_id')
            ->leftJoin('employees as approver', "{$formRequestTable}.approver_id", '=', 'approver.employee_id')
            ->where(function ($query) use ($search, $appraisalTable, $formRequestTable) {
                $query->whereRaw("CONCAT(evaluator.first_name, ' ', evaluator.last_name) LIKE '%" . $search . "%'")
                    ->orWhereRaw("CONCAT(appraisee.first_name, ' ', appraisee.last_name) LIKE '%" . $search . "%'")
                    ->orWhereRaw("CONCAT(approver.first_name, ' ', approver.last_name) LIKE '%" . $search . "%'")
                    ->orWhere("{$appraisalTable}.evaluation_type", 'like', '%' . $search . '%')
                    ->orWhere("{$formRequestTable}.status", 'like', '%' . $search . '%')
                    ->orWhere("{$formRequestTable}.request", 'like', '%' . $search . '%')
                    ->orWhere("{$formRequestTable}.created_at", 'like', '%' . $search . '%');
        })
        ->orderBy("{$formRequestTable}.created_at", 'desc')
        ->paginate(10);

        // Map the data for the response
        $formattedRequests = $userRequests->map(function ($request) use ($appraisalTable, $formRequestTable) {
        
            $timestamp = $request['created_at'];
            $dateSent = date('F j, Y H:i:s', strtotime($timestamp));
            
            $locks = [];
            $appraisalData = Appraisals::from($appraisalTable)
            ->where('appraisal_id', $request->appraisal_id)
            ->first([
                'kra_locked', 
                'pr_locked', 
                'eval_locked', 
                'locked', 
                'evaluation_type',
                'employee_id',
                'evaluator_id', 
            ]);

            $requestsData = Requests::from($formRequestTable)
            ->where('request_id', $request->request_id)
            ->value('approver_id');

            if ($appraisalData || $requestsData) {
                $locks['kra'] = $appraisalData->kra_locked == 1;
                $locks['pr'] = $appraisalData->pr_locked == 1;
                $locks['eval'] = $appraisalData->eval_locked == 1;
                // $locks['lock'] = $appraisalData->locked !== 1;

                $appraisal_type = $appraisalData->evaluation_type;
                $appraisal_type = ucwords($appraisal_type);

                $requester_name = Employees::where('employee_id', $appraisalData->evaluator_id)
                    ->first(['first_name', 'last_name']);
                
                $appraisee_name = Employees::where('employee_id', $appraisalData->employee_id)
                    ->first(['first_name', 'last_name']);
                
                // Concatenate first_name and last_name
                $requester_full_name = $requester_name['first_name'] . ' ' . $requester_name['last_name'];
                $appraisee_full_name = $appraisee_name['first_name'] . ' ' . $appraisee_name['last_name'];

                if ($requestsData !== null) {
                    // Fetch approver information only if $requestsData is not null
                    $approver_name = Employees::where('employee_id', $requestsData)
                        ->first(['first_name', 'last_name']);

                    // Concatenate first_name and last_name
                    $approver_full_name = $approver_name['first_name'] . ' ' . $approver_name['last_name'];
                } else {
                    // Set default value if $requestsData is null
                    $approver_full_name = "-";
                }

            }

            return [
                'request_id' => $request->request_id,
                'appraisal_id' => $request->appraisal_id,
                'requester' => $requester_full_name,
                'appraisal_type' => $appraisal_type,
                'appraisee' => $appraisee_full_name,
                'request' => $request->request,
                'locks' => $locks, 
                'date_sent' => $dateSent,
                'approver' => $approver_full_name ? $approver_full_name : '-',
                'status' => $request->status,
                'action' => $request->action,
                'feedback' => $request->feedback,   
            ];
        });

        $queries = DB::getQueryLog();

        $paginationData = [
            'current_page' => $userRequests->currentPage(),
            'from' => $userRequests->firstItem(),
            'last_page' => $userRequests->lastPage(),
            'last_page_url' => $userRequests->url($userRequests->lastPage()),
            'next_page_url' => $userRequests->nextPageUrl(),
            'path' => $userRequests->url(1),
            'per_page' => $userRequests->perPage(),
            'prev_page_url' => $userRequests->previousPageUrl(),
            'to' => $userRequests->lastItem(),
            'total' => $userRequests->total(),
        ];

        return response()->json([
            'data' => $formattedRequests,
            'pagination' => $paginationData,
            'queries' => $queries, // Return the executed queries
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
            
                if ($appraisal->eval_locked) {
                    Log::debug('Appraisal is locked');
                    $appraisal->update(['locked' => 0]);
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
