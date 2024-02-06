<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Appraisals;
use App\Models\EvalYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function displaySuperAdminDashboard()
    {
        if (session()->has('account_id')) {
            return view('superadmin-pages.super_admin_dashboard');
        } else {
            return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
        }
    }

    public function getNotifications()
    {
        if (!session()->has('account_id')) {
            return view('auth.login');
        }

        $activeYear = EvalYear::where('status', 'active')->first();
        $currentDate = Carbon::now();

        $notifications = [];

        if ($activeYear) {
            $schoolYear = $activeYear->sy_start . ' - ' . $activeYear->sy_end;

            $dateStart = Carbon::parse($activeYear->kra_start);
            $dateEnd = Carbon::parse($activeYear->eval_end);
            $fiveDaysAfterStart = $dateStart->copy()->addDays(5);
            $fiveDaysBeforeEnd = $dateEnd->copy()->subDays(5);

            $accountId = session('account_id');
            $pendingAppraisalsCount = Appraisals::where('evaluator_id', $accountId)
                ->whereNull('date_submitted')
                ->count();

            if ($currentDate <= $fiveDaysAfterStart) {
                $notifications[] = "Kindly check the Employees table.";
            }
            if ($pendingAppraisalsCount > 0) {
                $notifications[] = "Kindly check the Employees table.";
            }
            if ($currentDate >= $fiveDaysBeforeEnd) {
                $notifications[] = "Kindly check the Employees table.";

                if ($currentDate > $dateEnd) {
                    $notifications[] = "Kindly check the Employees table.";
                }
            }
        } else {
            $notifications[] = "Kindly check the Employees table.";
        }
        return response()->json(['notifications' => $notifications]);
    }
}
