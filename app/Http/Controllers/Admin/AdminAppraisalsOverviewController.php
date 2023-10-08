<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\AdminAppraisals;
use App\Models\EvalYear;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AdminAppraisalsOverviewController extends Controller
{
  public function displayAdminAppraisalsOverview()
  {
    if (session()->has('account_id')) {
      $evaluationYears = EvalYear::all();
      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      return view('admin-pages.admin_appraisals_overview', compact('evaluationYears', 'activeEvalYear'));
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadAdminAppraisals(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

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
      $table = 'appraisals_' . $selectedYear;

      $appraisals = Appraisals::from($table)
        ->with('employee')
        ->whereExists(function ($query) use ($search, $table) {
          $query->selectRaw(1)
            ->from('employees')
            ->whereRaw("$table.employee_id = employees.employee_id")
            ->where(function ($innerQuery) use ($search) {
              $innerQuery->orWhere('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        })
        ->paginate(40);

    } elseif ($activeEvalYear) {

      $sy_start = $activeEvalYear->sy_start;
      $sy_end = $activeEvalYear->sy_end;

      $selectedYearDates = $activeEvalYear;

      $appraisals = Appraisals::with('employee')
        ->whereHas('employee', function ($query) use ($search) {
          if ($search) {
            $query->where('first_name', 'like', '%' . $search . '%')
              ->orWhere('last_name', 'like', '%' . $search . '%');
          }
        })
        ->paginate(40);

    } else {
      return response()->json(['success' => false, 'error' => 'There is no selected nor ongoing year.']);
    }

    $groupedAppraisals = [];
    foreach ($appraisals as $appraisal) {
      $employeeId = $appraisal->employee->employee_id;
      if (!isset($groupedAppraisals[$employeeId])) {
        $groupedAppraisals[$employeeId] = [
          'employee' => $appraisal->employee,
          'appraisals' => [],
        ];
      }
      $groupedAppraisals[$employeeId]['appraisals'][] = $appraisal;
    }

    return response()->json(['success' => true, 'groupedAppraisals' => $groupedAppraisals, 'selectedYearDates' => $selectedYearDates,'appraisals' => $appraisals, // Include paginated appraisals
  ]);
  }


  public function loadSelfEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_self_evaluation');
  }

  public function loadISEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_is_evaluation');
  }

  public function loadICEvaluationForm()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    return view('admin-pages.admin_ic_evaluation');
  }

  public function loadSignatureOverview(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $employeeID = $request->input('employeeID');
    $sy = $request->input('selectedYear');

    if ($sy !== null) {
      $table = 'appraisals_' . $sy;

      $appraisalsModel = new Appraisals;
      $appraisalsModel->setTable($table);
      $appraisals = $appraisalsModel->where('employee_id', $employeeID)
        ->with(['employee', 'signatures', 'evaluator'])
        ->get();
    } else {
      $appraisals = Appraisals::where('employee_id', $employeeID)
        ->with(['employee', 'signatures', 'evaluator'])
        ->get();
    }

    return response()->json(['success' => true, 'appraisals' => $appraisals]);
  }


  public function loadSignature(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalID = $request->input('appraisalID');
    $sy = $request->input('selectedYear');

    if ($sy !== null) {
      $signatureTable = 'signature_' . $sy;
      $signatureModel = new Signature;
      $signatureModel->setTable($signatureTable);
      $signature = $signatureModel->where('appraisal_id', $appraisalID)->first();
    } else {
      $signature = Signature::where('appraisal_id', $appraisalID)->first();
    }

    $sign_data = null;

    if ($signature) {
      $sign_data = $signature->sign_data;
    }

    return response()->json([
      'success' => true,
      'sign_data' => $sign_data,
    ]);
  }

  public function lockUnlockAppraisal(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalID = $request->input('appraisalID');
    $appraisal = Appraisals::find($appraisalID);

    if ($appraisal) {
      $locked = $appraisal->locked;

      $appraisal->update(['locked' => !$locked]);

      return response()->json(['success' => true, 'locked' => !$locked]);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
    }
  }

  public function toggleKRALock(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalID = $request->input('appraisalID');
    $appraisal = Appraisals::find($appraisalID);

    if ($appraisal) {
      $locked = $appraisal->kra_locked;

      $appraisal->update(['kra_locked' => !$locked]);

      return response()->json(['success' => true, 'locked' => !$locked]);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
    }
  }

  public function togglePRLock(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalID = $request->input('appraisalID');
    $appraisal = Appraisals::find($appraisalID);

    if ($appraisal) {
      $locked = $appraisal->kra_locked;

      $appraisal->update(['pr_locked' => !$locked]);

      return response()->json(['success' => true, 'locked' => !$locked]);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
    }
  }

  public function toggleEvalLock(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalID = $request->input('appraisalID');
    $appraisal = Appraisals::find($appraisalID);

    if ($appraisal) {
      $locked = $appraisal->kra_locked;

      $appraisal->update(['eval_locked' => !$locked]);

      return response()->json(['success' => true, 'locked' => !$locked]);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
    }
  }
}