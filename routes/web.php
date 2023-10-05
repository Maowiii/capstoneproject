<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\AdminAppraisalsOverviewController;
use App\Http\Controllers\Admin\EditableAppraisalFormController;
use App\Http\Controllers\Admin\EditableInternalCustomerFormController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EvaluationYearController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminInternalCustomerController;
use App\Http\Controllers\Admin\DepartmentalAnalyticsController;
use App\Http\Controllers\ContractualEmployee\CEDashboardController;
use App\Http\Controllers\ContractualEmployee\CEICOverviewController;
use App\Http\Controllers\ContractualEmployee\CEInternalCustomerController;
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

/* ----- ADMIN ----- */
// Dashboard 
Route::get('/dashboard-admin', [AdminDashboardController::class, 'displayAdminDashboard'])->name('viewAdminDashboard');
Route::get('/dashboard-admin/get-departments-table', [AdminDashboardController::class, 'loadDepartmentTable'])->name('ad.loadDepartmentTable');
Route::get('/dashboard-admin/department', [DepartmentalAnalyticsController::class, 'displayDepartmentalAnalytics'])->name('ad.viewDepartment');
Route::get('/dashboard-admin/department/load-bc-questions', [DepartmentalAnalyticsController::class, 'loadBCQuestions'])->name('ad.loadBCQuestions');
Route::get('/dashboard-admin/department/load-ic-questions', [DepartmentalAnalyticsController::class, 'loadICQuestions'])->name('ad.loadICQuestions');
Route::get('/dashboard-admin/department/load-cards', [DepartmentalAnalyticsController::class, 'loadCards'])->name('ad.loadDepartmentalCards');
Route::get('/dashboard-admin/department/load-points-system', [DepartmentalAnalyticsController::class, 'loadPointsSystem'])->name('ad.loadPointsSystem');

//Appraisals Overview
Route::get('/admin-appraisals-overview', [AdminAppraisalsOverviewController::class, 'displayAdminAppraisalsOverview'])->name('viewAdminAppraisalsOverview');
Route::get('/admin-appraisals-overview/load-admin-table', [AdminAppraisalsOverviewController::class, 'loadAdminAppraisals'])->name('loadAdminAppraisals');
Route::get('/admin-appraisals-overview/self-evaluation-form', [AdminAppraisalsOverviewController::class, 'loadSelfEvaluationForm'])->name('ad.viewSelfEvaluationForm');
Route::get('/admin-appraisals-overview/is-evaluation-form', [AdminAppraisalsOverviewController::class, 'loadISEvaluationForm'])->name('ad.viewISEvaluationForm');
Route::get('/admin-appraisals-overview/ic-evaluation-form', [AdminAppraisalsOverviewController::class, 'loadICEvaluationForm'])->name('ad.viewICEvaluationForm');
Route::get('/admin-appraisals-overview/get-signatures', [AdminAppraisalsOverviewController::class, 'loadSignatureOverview'])->name('ad.loadSignaturesOverview');
Route::get('/admin-appraisals-overview/load-signature-image', [AdminAppraisalsOverviewController::class, 'loadSignature'])->name('ad.loadSignature');
Route::post('/admin-appraisals-overview/lock-unlock-appraisal', [AdminAppraisalsOverviewController::class, 'lockUnlockAppraisal'])->name('ad.lockUnlockAppraisal');
Route::post('/admin-appraisals-overview/toggle-kra-lock', [AdminAppraisalsOverviewController::class, 'toggleKRALock'])->name('ad.toggleKRALock');
Route::post('/admin-appraisals-overview/toggle-pr-lock', [AdminAppraisalsOverviewController::class, 'togglePRLock'])->name('ad.togglePRLock');
Route::post('/admin-appraisals-overview/toggle-eval-lock', [AdminAppraisalsOverviewController::class, 'toggleEvalLock'])->name('ad.toggleEvalLock');

Route::get('/admin-appraisals-overview/ic-evaluation-form', [AdminInternalCustomerController::class, 'loadICEvaluationForm'])->name('ad.viewICEvaluationForm');
Route::get('/admin-appraisals-overview/get-IC-questions', [AdminInternalCustomerController::class, 'getICQuestions'])->name('ad.getICQuestions');
Route::get('/admin-appraisals-overview/get-IC-saved-scores', [AdminInternalCustomerController::class, 'getSavedICScores'])->name('ad.getICScores');
Route::post('/admin-appraisals-overview/get-comments-and-suggestions', [AdminInternalCustomerController::class, 'getCommentsAndSuggestions'])->name('ad.getICCommentsAndSuggestions');
Route::get('/admin-appraisals-overview/load-ic-signatures', [AdminInternalCustomerController::class, 'loadSignatures'])->name('ad.loadICSignatures');

// Employee User Table
Route::get('/employees', [EmployeeController::class, 'displayEmployeeTable'])->name('viewEmployeeTable');
Route::get('/employees-data', [EmployeeController::class, 'getData'])->name('ad.getEmployeesData');
Route::post('/employees/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
Route::post('/employees/add-new-employee', [EmployeeController::class, 'addEmployee'])->name('add-new-employee');
Route::post('/employees/reset-password', [EmployeeController::class, 'employeeResetPassword'])->name('employeeResetPassword');
Route::post('/employees/edit-employee', [EmployeeController::class, 'editEmployee'])->name('ad.editEmployee');
Route::post('/employees/save-employee', [EmployeeController::class, 'saveEmployee'])->name('ad.saveEmployee');

// Evaluation Year
Route::get('/evaluation-year', [EvaluationYearController::class, 'viewEvaluationYears'])->name('viewEvaluationYears');
Route::get('/evaluation-year/displayEvaluationYear', [EvaluationYearController::class, 'displayEvaluationYear'])->name('displayEvaluationYear');
Route::post('/evaluation-year/toggle-eval-year-status', [EvaluationYearController::class, 'toggleEvalYearStatus'])->name('ad.toggleEvalYearStatus');

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
Route::get('/editable-internal-customer-form/formChecker', [EditableInternalCustomerFormController::class, 'formChecker'])->name('ad.formChecker');

/* ----- IMMEDIATE SUPERIOR ----- */
// Dashboard
Route::get('/dashboard-immediate-superior', [ISDashboardController::class, 'displayISDashboard'])->name('viewISDashboard');
Route::post('/dashboard-immediate-superior/submit-position', [ISDashboardController::class, 'submitISPosition'])->name('is.submitISPosition');
Route::get('/dashboard-immediate-superior/get-notifications', [ISDashboardController::class, 'getNotifications'])->name('is.getNotifications');

// Appraisals Overview
Route::get('/is-appraisals-overview', [ISAppraisalsOverviewController::class, 'displayISAppraisalsOverview'])->name('viewISAppraisalsOverview');
Route::get('/is-appraisals-overview/get-data', [ISAppraisalsOverviewController::class, 'getData'])->name('getISData');
Route::get('/is-appraisals-overview/get-employees', [ISAppraisalsOverviewController::class, 'getEmployees'])->name('getEmployeesData');
Route::get('/is-appraisal/{appraisal_id}', [SelfEvaluationController::class, 'viewAppraisal'])->name('viewAppraisal');
Route::get('/get-KRA-data', [ISAppraisalController::class, 'getKRA'])->name('getKRA');
Route::post('/save-is-appraisal', [ISAppraisalController::class, 'saveISAppraisal'])->name('saveISAppraisal');
Route::post('/delete-kra', [ISAppraisalController::class, 'deleteKRA'])->name('deleteKRA');
Route::post('/delete-wpa', [ISAppraisalController::class, 'deleteWPA'])->name('delete-wpa');
Route::post('/delete-ldp', [ISAppraisalController::class, 'deleteLDP'])->name('deleteLDP');
Route::post('/assign-internal-customer', [ISAppraisalsOverviewController::class, 'assignInternalCustomer'])->name('assignInternalCustomer');


// Settings
Route::get('/settings', [SettingsController::class, 'displaySettings'])->name('viewSettings');
Route::get('/employee-info', [SettingsController::class, 'displayEmployeeInfo'])->name('employeeInfo');



/* ----- PERMANENT EMPLOYEE ----- */
// Dashboard
Route::get('/pe-dashboard', [PEDashboardController::class, 'displayPEDashboard'])->name('viewPEDashboard');
Route::get('/get-is-appraisal-data', [SelfEvaluationController::class, 'showAppraisalForm'])->name('getISAppraisalData');
Route::post('/pe-dashboard/submit-fist-login', [PEDashboardController::class, 'submitPEFirstLogin'])->name('pe.submitFirstLogin');
Route::get('/pe-dashboard/get-notifications', [PEDashboardController::class, 'getNotifications'])->name('pe.getNotifications');

// Appraisals Overview
Route::get('/pe-appraisals-overview', [PEAppraisalsController::class, 'displayPEAppraisalsOverview'])->name('viewPEAppraisalsOverview');
Route::get('/self-evaluation', [SelfEvaluationController::class, 'displaySelfEvaluationForm'])->name('viewSelfEvaluationForm');
Route::post('/self-evaluation/form-checker', [SelfEvaluationController::class, 'formChecker'])->name('pe.SEFormChecker');

Route::get('/self-evaluation/get-appraisal-questions', [SelfEvaluationController::class, 'getQuestions'])->name('pe.getAppraisalQuestions');
Route::get('/get-pe-appraisal-data', [SelfEvaluationController::class, 'getData'])->name('getPEData');
Route::get('/get-pe-KRA-data', [SelfEvaluationController::class, 'getPEKRA'])->name('getPEKRA');
Route::get('/pe-appraisal/{appraisal_id}', [SelfEvaluationController::class, 'viewAppraisal'])->name('viewPEAppraisal');
Route::post('/save-data-privacy', [SelfEvaluationController::class, 'saveEULA'])->name('saveEULA');
Route::post('/save-pe-appraisal', [SelfEvaluationController::class, 'savePEAppraisal'])->name('savePEAppraisal');

Route::post('/autosave-kra-field', [SelfEvaluationController::class, 'autosaveKRAField'])->name('autosaveKRAField');
Route::post('/autosave-wpp-field', [SelfEvaluationController::class, 'autosaveWPPField'])->name('autosaveWPPField');
Route::post('/autosave-ldp-field', [SelfEvaluationController::class, 'autosaveLDPField'])->name('autosaveLDPField');
Route::post('/autosave-jic-field', [SelfEvaluationController::class, 'autosaveJICField'])->name('autosaveJICField');

Route::get('/pe-go-appraisal/{appraisal_id}', [SelfEvaluationController::class, 'viewGOAppraisal'])->name('viewPEGOAppraisal');

// Internal Customers
Route::get('/pe-internal-customers-overview', [PEInternalCustomerController::class, 'displayICOverview'])->name('viewICOverview');
Route::get('/pe-internal-customers-overview/getICAssign', [PEInternalCustomerController::class, 'getICAssign'])->name('getICAssign');
Route::get('/pe-internal-customers/getICQuestions', [PEInternalCustomerController::class, 'getICQuestions'])->name('pe.getICQuestions');
Route::get('/pe-internal-customers/appraisalForm', [PEInternalCustomerController::class, 'showAppraisalForm'])->name('appraisalForm');
Route::get('/pe-internal-customers/getICScores', [PEInternalCustomerController::class, 'getSavedICScores'])->name('getSavedICScores');
Route::post('/pe-internal-customer-overview/saveICScores', [PEInternalCustomerController::class, 'saveICScores'])->name('saveICScores');
Route::post('/pe-internal-customers/updateService', [PEInternalCustomerController::class, 'updateService'])->name('updateService');
Route::post('/pe-internal-customers/updateSuggestion', [PEInternalCustomerController::class, 'updateSuggestion'])->name('updateSuggestion');
Route::post('/pe-internal-customers/getCommentsAndSuggestions', [PEInternalCustomerController::class, 'getCommentsAndSuggestions'])->name('getCommentsAndSuggestions');
Route::get('/pe-internal-customers/get-signatures', [PEInternalCustomerController::class, 'loadSignatures'])->name('pe.loadSignatures');
Route::post('/pe-internal-customers/submit-ic-signature', [PEInternalCustomerController::class, 'submitICSignature'])->name('pe.submitICSignature');
Route::post('/pe-internal-customer/form-checker', [PEInternalCustomerController::class, 'formChecker'])->name('pe.ICFormChecker');

// Signature of Party Involved

/* ----- CONTRACTUAL EMPLOYEE ----- */
// Dashboard
Route::get('/dashboard-contractual-employee', [CEDashboardController::class, 'displayCEDashboard'])->name('viewCEDashboard');
Route::get('/dashboard-contractual-employee/get-notifications', [CEDashboardController::class, 'getNotifications'])->name('ce.getNotifications');
Route::get('/dashboard-contractual-employee/get-remaining-appraisals', [CEDashboardController::class, 'getRemainingAppraisals'])->name('ce.getRemainingAppraisals');
Route::post('/dashboard-contractual-employee/submit-first-login', [CEDashboardController::class, 'submitFirstLogin'])->name('ce.submitFirstLogin');

// Internal Customers
Route::get('/ce-internal-customers-overview', [CEInternalCustomerController::class, 'displayCEICOverview'])->name('ce.viewICOverview');
Route::get('/ce-internal-customers/appraisalForm', [PEInternalCustomerController::class, 'showAppraisalForm'])->name('ce.viewICAppraisalForm');

// Settings
Route::post('/setttings/change-password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');