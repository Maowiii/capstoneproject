<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\AdminAppraisalsOverviewController;
use App\Http\Controllers\Admin\EditableAppraisalFormController;
use App\Http\Controllers\Admin\EditableInternalCustomerFormController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EvaluationYearController;
use App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\ImmediateSuperior\ISAppraisalsOverviewController;
use App\Http\Controllers\ImmediateSuperior\ISDashboardController;
use App\Http\Controllers\ImmediateSuperior\ISAppraisalController;

use App\Http\Controllers\PermanentEmployee\PEAppraisalsController;
use App\Http\Controllers\PermanentEmployee\PEDashboardController;
use App\Http\Controllers\PermanentEmployee\PEInternalCustomerController;
use App\Http\Controllers\PermanentEmployee\SelfEvaluationController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth Controller
Route::get('/', function () {
  return redirect()->route('viewLogin');
});

Route::get('/login', [AuthController::class, 'displayLogin'])->name('viewLogin');
Route::get('/two-factor-auth', [AuthController::class, 'displayTwoFactorAuth'])->name('viewTwoFactorAuth');
Route::get('/reset-password-1', [AuthController::class, 'displayResetPassword'])->name('viewResetPassword');
Route::get('/reset-password-2', [AuthController::class, 'displayResetPassword2'])->name('viewResetPassword2');
Route::get('/reset-password-3', [AuthController::class, 'displayResetPassword3'])->name('viewResetPassword3');
Route::post('/login-user', [AuthController::class, 'login'])->name('login-user');
Route::post('/two-factor', [AuthController::class, 'verifyTwoFactorAuth'])->name('verify-code');
Route::post('/reset-password/verify-email', [AuthController::class, 'step1_VerifyEmail'])->name('reset-password-verify-email');
Route::post('/reset-password/verify-code', [AuthController::class, 'step2_VerifyCode'])->name('reset-password-verify-code');
Route::post('/reset-password/reset', [AuthController::class, 'step3_ResetPassword'])->name('reset-password');
Route::get('two-factor/resend-code', [AuthController::class, 'sendCode'])->name('resend-code');

// Dashboard 
Route::get('/dashboard-admin', [AdminDashboardController::class, 'displayAdminDashboard'])->name('viewAdminDashboard');
Route::get('/dashboard-immediate-superior', [ISDashboardController::class, 'displayISDashboard'])->name('viewISDashboard');

/* ----- ADMIN ----- */
//Appraisals Overview
Route::get('/admin-appraisals-overview', [AdminAppraisalsOverviewController::class, 'displayAdminAppraisalsOverview'])->name('viewAdminAppraisalsOverview');
Route::get('/admin-appraisals-overview/load-admin-table', [AdminAppraisalsOverviewController::class, 'loadAdminAppraisals'])->name('loadAdminAppraisals');
Route::get('/admin-appraisals-overview/self-evaluation-form', [AdminAppraisalsOverviewController::class, 'loadSelfEvaluationForm'])->name('loadAdminSelfEvaluationForm');
Route::get('/admin-appraisals-overview/is-evaluation-form', [AdminAppraisalsOverviewController::class, 'loadISEvaluationForm'])->name('loadAdminISEvaluationForm');
Route::get('/admin-appraisals-overview/ic-evaluation-form', [AdminAppraisalsOverviewController::class, 'loadICEvaluationForm'])->name('loadAdminICEvaluationForm');

// Employee User Table
Route::get('/employees', [EmployeeController::class, 'displayEmployeeTable'])->name('viewEmployeeTable');
Route::get('/employees-data', [EmployeeController::class, 'getData'])->name('employees.getData');
Route::post('/employees/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
Route::post('/employees/add-new-employee', [EmployeeController::class, 'addEmployee'])->name('add-new-employee');
Route::post('/employees/reset-password', [EmployeeController::class, 'employeeResetPassword'])->name('employeeResetPassword');

// Evaluation Year
Route::get('/evaluation-year', [EvaluationYearController::class, 'viewEvaluationYears'])->name('viewEvaluationYears');
Route::get('/evaluation-year/displayEvaluationYear', [EvaluationYearController::class, 'displayEvaluationYear'])->name('displayEvaluationYear');

Route::post('/evaluation-year/add-eval-year', [EvaluationYearController::class, 'addEvalYear'])->name('addEvalYear');
Route::post('/evaluation-year/confirm-eval-year', [EvaluationYearController::class, 'confirmEvalYear'])->name('confirmEvalYear');

// Editable Appraisal Form
Route::get('/editable-appraisal-form', [EditableAppraisalFormController::class, 'displayEditableAppraisalForm'])->name('viewEditableAppraisalForm');
Route::get('/editable-appraisal-form/getAppraisalQuestions', [EditableAppraisalFormController::class, 'getAppraisalQuestions'])->name('getAppraisalQuestions');
Route::post('/editable-appraisal-form/updateAppraisalQuestions/{questionId}', [EditableAppraisalFormController::class, 'updateAppraisalQuestions'])->name('updateAppraisalQuestions');
Route::post('/editable-appraisal-form/deleteAppraisalQuestions/{questionId}', [EditableAppraisalFormController::class, 'deleteAppraisalQuestions'])->name('deleteAppraisalQuestions');
Route::post('/editable-appraisal-form/addAppraisalQuestions', [EditableAppraisalFormController::class, 'addAppraisalQuestions'])->name('addAppraisalQuestions');

// Editable Internal Customer Form
Route::get('/editable-internal-customer-form', [EditableInternalCustomerFormController::class, 'displayEditableInternalCustomerForm'])->name('viewEditableInternalCustomerForm');
Route::get('/editable-internal-customer-form/getICQuestions', [EditableInternalCustomerFormController::class, 'getICQuestions'])->name('getICQuestions');
Route::post('/editable-internal-customer-form/updateICQuestions/{questionId}', [EditableInternalCustomerFormController::class, 'updateICQuestions'])->name('updateICQuestions');
Route::post('/editable-internal-customer-form/deleteICQuestions/{questionId}', [EditableInternalCustomerFormController::class, 'deleteICQuestions'])->name('deleteICQuestions');
Route::post('/editable-internal-customer-form/addICQuestions', [EditableInternalCustomerFormController::class, 'addICQuestions'])->name('addICQuestions');

/* ----- IMMEDIATE SUPERIOR ----- */
// Appraisals Overview
Route::get('/is-appraisals-overview', [ISAppraisalsOverviewController::class, 'displayISAppraisalsOverview'])->name('viewISAppraisalsOverview');
Route::get('/is-appraisals-overview/get-data', [ISAppraisalsOverviewController::class, 'getData'])->name('getISData');
Route::get('/is-appraisals-overview/get-employees', [ISAppraisalsOverviewController::class, 'getEmployees'])->name('getEmployeesData');
Route::get('/is-appraisal/{appraisal_id}', [ISAppraisalController::class, 'viewAppraisal'])->name('viewAppraisal');
Route::get('/get-KRA-data', [ISAppraisalController::class, 'getKRA'])->name('getKRA');
Route::post('/save-is-appraisal', [ISAppraisalController::class, 'saveISAppraisal'])->name('saveISAppraisal');
Route::post('/delete-kra', [ISAppraisalController::class, 'deleteKRA'])->name('deleteKRA');

// Settings
Route::get('/settings', [SettingsController::class, 'displaySettings'])->name('viewSettings');

/* ----- PERMANENT EMPLOYEE ----- */
// Dashboard
Route::get('/pe-dashboard', [PEDashboardController::class, 'displayPEDashboard'])->name('viewPEDashboard');
Route::get('/get-is-appraisal-data', [SelfEvaluationController::class, 'showAppraisalForm'])->name('getISAppraisalData');

// Appraisals Overview
Route::get('/pe-appraisals-overview', [PEAppraisalsController::class, 'displayPEAppraisalsOverview'])->name('viewPEAppraisalsOverview');
Route::get('/self-evaluation', [SelfEvaluationController::class, 'displaySelfEvaluationForm'])->name('viewSelfEvaluationForm');
Route::get('/self-evaluation/get-appraisal-questions', [SelfEvaluationController::class, 'getQuestions'])->name('pe.getAppraisalQuestions');
Route::get('/get-pe-appraisal-data', [SelfEvaluationController::class, 'getData'])->name('getPEData');
Route::get('/get-pe-KRA-data', [SelfEvaluationController::class, 'getPEKRA'])->name('getPEKRA');
Route::get('/pe-appraisal/{appraisal_id}', [SelfEvaluationController::class, 'viewAppraisal'])->name('viewPEAppraisal');
Route::post('/save-pe-appraisal', [SelfEvaluationController::class, 'savePEAppraisal'])->name('savePEAppraisal');

// Internal Customers
Route::get('/pe-internal-customers-overview', [PEInternalCustomerController::class, 'displayICOverview'])->name('viewICOverview');
Route::get('/pe-internal-customers-overview/getICAssign', [PEInternalCustomerController::class, 'getICAssign'])->name('getICAssign');
Route::get('/pe-internal-customers/getICQuestions', [PEInternalCustomerController::class, 'getICQuestions'])->name('getICQuestions');
Route::get('/pe-internal-customers/appraisalForm', [PEInternalCustomerController::class, 'showAppraisalForm'])->name('appraisalForm');
Route::get('/pe-internal-customers/getICScores', [PEInternalCustomerController::class, 'getSavedICScores'])->name('getSavedICScores');
Route::post('/pe-internal-customer-overview/saveICScores', [PEInternalCustomerController::class, 'saveICScores'])->name('saveICScores');
Route::post('/pe-internal-customers/updateService', [PEInternalCustomerController::class, 'updateService'])->name('updateService');
Route::post('/pe-internal-customers/updateSuggestion', [PEInternalCustomerController::class, 'updateSuggestion'])->name('updateSuggestion');
Route::post('/pe-internal-customers/getCommentsAndSuggestions', [PEInternalCustomerController::class, 'getCommentsAndSuggestions'])->name('getCommentsAndSuggestions');

// Signature of Party Involved

