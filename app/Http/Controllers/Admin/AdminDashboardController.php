<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appraisals;
use App\Models\Departments;
use App\Models\EvalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
  public function displayAdminDashboard()
  {
    if (session()->has('account_id')) {
      $evaluationYears = EvalYear::all();
      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      return view('admin-pages.admin_dashboard', compact('evaluationYears', 'activeEvalYear'));
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function loadDepartmentTable(Request $request)
  {
    if (session()->has('account_id')) {

      $activeEvalYear = EvalYear::where('status', 'active')->first() ?? null;

      $search = $request->input('search');
      $selectedYear = $request->input('selectedYear');
      $page = $request->input('page');

      if ($selectedYear) {
        if ($search) {
          $table = 'appraisals_' . $selectedYear;
          $appraisalModel = new Appraisals;
          $appraisalModel->setTable($table);

        } else {
          $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
            ->orderBy('department_name')
            ->paginate(20);

          return response()->json(['success' => true, 'departments' => $departments]);
        }

      } elseif ($activeEvalYear) {
        if ($search) {
          $departments = Departments::where('department_name', 'LIKE', '%' . $search . '%')
            ->orderBy('department_name')
            ->paginate(20);

          return response()->json(['success' => true, 'departments' => $departments]);
        } else {
          $departments = Departments::orderBy('department_name')->paginate(20);
          Log::debug($departments);


          return response()->json(['success' => true, 'departments' => $departments]);
        }
      } else {
        return response()->json(['success' => false]);
      }
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }
}