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

    $selectedYear = $request->input('selectedYear');
    $search = $request->input('search');

    $sy_start = null;
    $sy_end = null;
    $selectedYearDates = null;

    // If There is a selected year:
    if ($selectedYear) {
      $parts = explode('_', $selectedYear);

      if (count($parts) >= 2) {
        $sy_start = $parts[0];
        $sy_end = $parts[1];
      }

      $selectedYearDates = EvalYear::where('sy_start', $sy_start)->first();

      if (!$selectedYearDates) {
        return response()->json(['success' => false, 'error' => 'Selected year not found.']);
      }

      // If there is a search query based on name or employee number
      if (Appraisals::tableExists()) {
        if ($search) {
          $table = 'appraisals_' . $selectedYear;
          $appraisalModel = new Appraisals;
          $appraisalModel->setTable($table);

          $appraisals = $appraisalModel->where(function ($query) use ($search, $table) {
            $query->whereExists(function ($subQuery) use ($search, $table) {
              $subQuery->selectRaw(1)
                ->from('employees')
                ->whereRaw("$table.employee_id = employees.employee_id")
                ->where(function ($innerQuery) use ($search) {
                  $innerQuery->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            });
          })->get();

        } else {
          $table = 'appraisals_' . $selectedYear;
          $appraisalsModel = new Appraisals;
          $appraisalsModel->setTable($table);
          $appraisals = $appraisalsModel->get();
        }
      } else {
        return response()->json(['success' => false, 'error' => 'There is no existing evaluation year.']);
      }
    } else {
      // Active Year Condition (No Selected Year)
      $selectedYearDates = EvalYear::where('status', 'active')->first();

      if (Appraisals::tableExists()) {
        if ($search) {
          $appraisals = Appraisals::with('employee')
            ->whereHas('employee', function ($query) use ($search) {
              $query->Where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%');
            })
            ->get();
        } else {
          $appraisals = Appraisals::with([
            'employee' => function ($query) {
              $query->whereHas('account', function ($subQuery) {
                $subQuery->whereIn('type', ['PE', 'IS', 'CE']);
              });
            }
          ])->get();
        }
      } else {
        return response()->json(['success' => false, 'error' => 'There is no existing evaluation year.']);
      }

      if (!$selectedYearDates) {
        return response()->json(['success' => false, 'error' => 'Selected year not found.']);
      }
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

    return response()->json(['success' => true, 'groupedAppraisals' => $groupedAppraisals, 'selectedYearDates' => $selectedYearDates]);
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
}