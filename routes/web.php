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
use App\Http\Controllers\Admin\EmployeeAnalyticsController;
use App\Http\Controllers\Admin\AdminRequestOverviewController;
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
use App\Models\EvalYear;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Accounts;

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
Route::get('/logout-user', [AuthController::class, 'logout'])->name('logout-user');


/* ----- ADMIN ----- */
// Dashboard
// General Analytics
Route::get('/dashboard-admin', [AdminDashboardController::class, 'displayAdminDashboard'])->name('viewAdminDashboard');
Route::get('/dashboard-admin/load-cards', [AdminDashboardController::class, 'loadCards'])->name('ad.loadCards');
Route::get('/dashboard-admin/get-final-scores-per-year', [AdminDashboardController::class, 'getFinalScoresPerYear'])->name('ad.getFinalScoresPerYear');
Route::get('/dashboard-admin/get-departmental-trends', [AdminDashboardController::class, 'getDepartmentalTrends'])->name('ad.getDepartmentalTrends');
Route::get('/dashboard-admin/get-departments-table', [AdminDashboardController::class, 'loadDepartmentTable'])->name('ad.loadDepartmentTable');
Route::get('/dashboard-admin/load-points-system', [AdminDashboardController::class, 'loadPointsSystem'])->name('ad.loadDashboardPointsSystem');

// Route::get('/dashboard-admin/load-employees', [AdminDashboardController::class, 'loadEmployees'])->name('ad.loadEmployees');

Route::get('/dashboard-admin/load-sid-questions', [AdminDashboardController::class, 'loadSIDQuestions'])->name('ad.loadDashboardSIDQuestions');
Route::get('/dashboard-admin/load-sid-chart', [AdminDashboardController::class, 'loadSIDChart'])->name('ad.loadSIDChart');
Route::get('/dashboard-admin/load-sr-questions', [AdminDashboardController::class, 'loadSRQuestions'])->name('ad.loadDashboardSRQuestions');
Route::get('/dashboard-admin/load-sr-chart', [AdminDashboardController::class, 'loadSRChart'])->name('ad.loadSRChart');
Route::get('/dashboard-admin/load-s-questions', [AdminDashboardController::class, 'loadSQuestions'])->name('ad.loadDashboardSQuestions');
Route::get('/dashboard-admin/load-s-chart', [AdminDashboardController::class, 'loadSChart'])->name('ad.loadSChart');
Route::get('/dashboard-admin/load-ic-questions', [AdminDashboardController::class, 'loadICQuestions'])->name('ad.loadDashboardICQuestions');
Route::get('/dashboard-admin/load-ic-chart', [AdminDashboardController::class, 'loadICChart'])->name('ad.loadICChart');
Route::get('/dashboard-admin/load-category', [AdminDashboardController::class, 'loadPointCategory'])->name('ad.viewCategory');
Route::get('/dashboard-admin/view-score', [AdminDashboardController::class, 'viewScore'])->name('ad.viewScoreModal');
Route::get('/dashboard-admin/print/admin', [AdminDashboardController::class, 'printAdminDashboard'])->name('ad.printAdminDashboard');

// Departmental Analytics
Route::get('/dashboard-admin/department', [DepartmentalAnalyticsController::class, 'displayDepartmentalAnalytics'])->name('ad.viewDepartment');
Route::post('/dashboard-admin/department/line-chart', [DepartmentalAnalyticsController::class, 'getFinalScoresPerDepartment'])->name('ad.departmentLineChart');

Route::get('/dashboard-admin/department/load-cards', [DepartmentalAnalyticsController::class, 'loadCards'])->name('ad.loadDepartmentalCards');
Route::get('/dashboard-admin/department/load-points-system', [DepartmentalAnalyticsController::class, 'loadPointsSystem'])->name('ad.loadDepartmentalPointsSystem');
Route::get('/dashboard-admin/department/load-employees', [DepartmentalAnalyticsController::class, 'loadEmployees'])->name('ad.loadDepartmentalEmployees');
Route::get('/dashboard-admin/department/view-category', [DepartmentalAnalyticsController::class, 'loadPointCategory'])->name('ad.viewDepartmentalCategory');

Route::get('/dashboard-admin/department/load-sid-questions', [DepartmentalAnalyticsController::class, 'loadSIDQuestions'])->name('ad.loadDepartmentalSIDQuestions');
Route::get('/dashboard-admin/department/load-sid-chart', [DepartmentalAnalyticsController::class, 'loadSIDChart'])->name('ad.loadDepartmentalSIDChart');

Route::get('/dashboard-admin/department/load-sr-questions', [DepartmentalAnalyticsController::class, 'loadSRQuestions'])->name('ad.loadDepartmentalSRQuestions');
Route::get('/dashboard-admin/department/load-sr-chart', [DepartmentalAnalyticsController::class, 'loadSRChart'])->name('ad.loadDepartmentalSRChart');

Route::get('/dashboard-admin/department/load-s-questions', [DepartmentalAnalyticsController::class, 'loadSQuestions'])->name('ad.loadDepartmentalSQuestions');
Route::get('/dashboard-admin/department/load-s-chart', [DepartmentalAnalyticsController::class, 'loadSChart'])->name('ad.loadDepartmentalSChart');

Route::get('/dashboard-admin/department/load-ic-questions', [DepartmentalAnalyticsController::class, 'loadICQuestions'])->name('ad.loadDepartmentalICQuestions');
Route::get('/dashboard-admin/department/load-ic-chart', [DepartmentalAnalyticsController::class, 'loadICChart'])->name('ad.loadDepartmentalICChart');

Route::get('/dashboard-admin/department/view-score', [DepartmentalAnalyticsController::class, 'viewScore'])->name('ad.viewDepartmentalScoreModal');

Route::get('/dashboard-admin/print/department', [DepartmentalAnalyticsController::class, 'printDepartmentDashboard'])->name('ad.printDepartmentDashboard');

// Employee Analytics
Route::get('/dashboard-admin/employee', [EmployeeAnalyticsController::class, 'displayEmployeeAnalytics'])->name('ad.viewEmployeeAnalytics');
Route::get('/dashboard-admin/employee/get-employee-information', [EmployeeAnalyticsController::class, 'getEmployeeInformation'])->name('ad.getEmployeeInformation');
Route::get('/dashboard-admin/employee/load-yearly-trend', [EmployeeAnalyticsController::class, 'loadYearlyTrend'])->name('ad.loadEmployeeYearlyTrend');

Route::get('/dashboard-admin/employee/load-kra-trend', [EmployeeAnalyticsController::class, 'loadKRATrend'])->name('ad.loadEmployeeKRATrend');
Route::get('/dashboard-admin/employee/load-kra', [EmployeeAnalyticsController::class, 'loadKRA'])->name('ad.loadEmployeeKRA');

Route::get('/dashboard-admin/employee/load-sid-questions', [EmployeeAnalyticsController::class, 'loadSIDQuestions'])->name('ad.loadEmployeeSIDQuestions');
Route::get('/dashboard-admin/employee/load-sid-chart', [EmployeeAnalyticsController::class, 'loadSIDChart'])->name('ad.loadEmployeeSIDChart');

Route::get('/dashboard-admin/employee/load-sr-questions', [EmployeeAnalyticsController::class, 'loadSRQuestions'])->name('ad.loadEmployeeSRQuestions');
Route::get('/dashboard-admin/employee/load-sr-chart', [EmployeeAnalyticsController::class, 'loadSRChart'])->name('ad.loadEmployeeSRChart');

Route::get('/dashboard-admin/employee/load-s-questions', [EmployeeAnalyticsController::class, 'loadSQuestions'])->name('ad.loadEmployeeSQuestions');
Route::get('/dashboard-admin/employee/load-s-chart', [EmployeeAnalyticsController::class, 'loadSChart'])->name('ad.loadEmployeeSChart');

Route::get('/dashboard-admin/employee/load-ic-questions', [EmployeeAnalyticsController::class, 'loadICQuestions'])->name('ad.loadEmployeeICQuestions');
Route::get('/dashboard-admin/employee/load-ic-chart', [EmployeeAnalyticsController::class, 'loadICChart'])->name('ad.loadEmployeeICChart');

Route::get('/dashboard-admin/print/employee', [EmployeeAnalyticsController::class, 'printEmployeeDashboard'])->name('ad.printEmployeeDashboard');

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
Route::post('/employees/import-new-employee', [EmployeeController::class, 'importEmployee'])->name('import-new-employee');
Route::post('/employees/reset-password', [EmployeeController::class, 'employeeResetPassword'])->name('employeeResetPassword');
Route::post('/employees/edit-employee', [EmployeeController::class, 'editEmployee'])->name('ad.editEmployee');
Route::post('/employees/save-employee', [EmployeeController::class, 'saveEmployee'])->name('ad.saveEmployee');

// Evaluation Year
Route::get('/evaluation-year', [EvaluationYearController::class, 'viewEvaluationYears'])->name('viewEvaluationYears');
Route::get('/evaluation-year/displayEvaluationYear', [EvaluationYearController::class, 'displayEvaluationYear'])->name('displayEvaluationYear');
Route::get('/evaluation-year/get-evaluation-weights', [EvaluationYearController::class, 'getEvalWeights'])->name('ad.getEvalWeights');
Route::post('/evaluation-year/toggle-eval-year-status', [EvaluationYearController::class, 'toggleEvalYearStatus'])->name('ad.toggleEvalYearStatus');

Route::post('evaluation-year/delete-eval', [EvaluationYearController::class, 'deleteEvalYear'])->name('ad.deleteEvalYear');

Route::post('/evaluation-year/add-eval-year', [EvaluationYearController::class, 'addEvalYear'])->name('addEvalYear');
Route::post('/evaluation-year/confirm-eval-year', [EvaluationYearController::class, 'confirmEvalYear'])->name('confirmEvalYear');

Route::post('/automateIC', [EvaluationYearController::class, 'automateIC'])->name('automateIC');

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

// Request Overview
Route::get('/admin-request', [AdminRequestOverviewController::class, 'viewRequestOverview'])->name('viewRequestOverview');
Route::get('/get-user-requests', [AdminRequestOverviewController::class, 'getUserRequests'])->name('getUserRequests');
Route::post('/approve-user-requests', [AdminRequestOverviewController::class, 'submitRequestApproval'])->name('submitRequestApproval');
Route::post('/disapprove-user-requests', [AdminRequestOverviewController::class, 'submitRequestDisapproval'])->name('submitRequestDisapproval');

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
Route::post('/get-final-scores', [ISAppraisalsOverviewController::class, 'getScoreSummary'])->name('getScoreSummary');

Route::post('/autosave-is-kra-field', [ISAppraisalController::class, 'autosaveKRAField'])->name('autosaveISKRAField');
Route::post('/autosave-is-wpp-field', [ISAppraisalController::class, 'autosaveWPPField'])->name('autosaveISWPPField');
Route::post('/autosave-is-ldp-field', [ISAppraisalController::class, 'autosaveLDPField'])->name('autosaveISLDPField');

// Settings
Route::get('/settings', [SettingsController::class, 'displaySettings'])->name('viewSettings');
Route::get('/employee-info', [SettingsController::class, 'displayEmployeeInfo'])->name('employeeInfo');
Route::post('/setttings/change-password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');

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
Route::post('/submit-request', [SelfEvaluationController::class, 'submitRequest'])->name('submitRequest');

Route::post('/autosave-kra-field', [SelfEvaluationController::class, 'autosaveKRAField'])->name('autosaveKRAField');
Route::post('/autosave-wpp-field', [SelfEvaluationController::class, 'autosaveWPPField'])->name('autosaveWPPField');
Route::post('/autosave-ldp-field', [SelfEvaluationController::class, 'autosaveLDPField'])->name('autosaveLDPField');
Route::post('/autosave-jic-field', [SelfEvaluationController::class, 'autosaveJICField'])->name('autosaveJICField');

Route::get('/view-greyedout-appraisal/{appraisal_id}', [SelfEvaluationController::class, 'viewGOAppraisal'])->name('viewPEGOAppraisal');

// Internal Customers
Route::get('/pe-internal-customers-overview', [PEInternalCustomerController::class, 'displayICOverview'])->name('viewICOverview');
Route::get('/pe-internal-customers-overview/getICAssign', [PEInternalCustomerController::class, 'getICAssign'])->name('getICAssign');
Route::get('/pe-internal-customers/getICQuestions', [PEInternalCustomerController::class, 'getICQuestions'])->name('pe.getICQuestions');
Route::get('/pe-internal-customers/appraisalForm', [PEInternalCustomerController::class, 'showAppraisalForm'])->name('appraisalForm');
Route::get('/pe-internal-customers/appraisal-ic-form', [PEInternalCustomerController::class, 'showICForm'])->name('pe.appraisalICForm');
Route::get('/pe-internal-customers/getICScores', [PEInternalCustomerController::class, 'getSavedICScores'])->name('getSavedICScores');
Route::post('/pe-internal-customer-overview/saveICScores', [PEInternalCustomerController::class, 'saveICScores'])->name('saveICScores');
Route::post('/pe-internal-customers/updateService', [PEInternalCustomerController::class, 'updateService'])->name('updateService');
Route::post('/pe-internal-customers/updateSuggestion', [PEInternalCustomerController::class, 'updateSuggestion'])->name('updateSuggestion');
Route::post('/pe-internal-customers/getCommentsAndSuggestions', [PEInternalCustomerController::class, 'getCommentsAndSuggestions'])->name('getCommentsAndSuggestions');
Route::get('/pe-internal-customers/get-signatures', [PEInternalCustomerController::class, 'loadSignatures'])->name('pe.loadSignatures');
Route::post('/pe-internal-customers/submit-ic-signature', [PEInternalCustomerController::class, 'submitICSignature'])->name('pe.submitICSignature');
Route::post('/pe-internal-customer/form-checker', [PEInternalCustomerController::class, 'formChecker'])->name('pe.ICFormChecker');
Route::get('/pe-internal-customers/get-appraisee-name', [PEInternalCustomerController::class, 'showAppraisalForm'])->name('showAppraisalForm');

/* ----- CONTRACTUAL EMPLOYEE ----- */
// Dashboard
Route::get('/dashboard-contractual-employee', [CEDashboardController::class, 'displayCEDashboard'])->name('viewCEDashboard');
Route::get('/dashboard-contractual-employee/get-notifications', [CEDashboardController::class, 'getNotifications'])->name('ce.getNotifications');
Route::get('/dashboard-contractual-employee/get-remaining-appraisals', [CEDashboardController::class, 'getRemainingAppraisals'])->name('ce.getRemainingAppraisals');
Route::post('/dashboard-contractual-employee/submit-first-login', [CEDashboardController::class, 'submitFirstLogin'])->name('ce.submitFirstLogin');

// Internal Customers
Route::get('/ce-internal-customers-overview', [CEInternalCustomerController::class, 'displayCEICOverview'])->name('ce.viewICOverview');
Route::get('/ce-internal-customers/appraisalForm', [PEInternalCustomerController::class, 'showICForm'])->name('ce.viewICAppraisalForm');

Route::get('/calculate-final-scores', [SelfEvaluationController::class, 'calculateAndStoreFinalScoresForAllEmployees']);

//TDM (Testing)
Route::get('/TDM/accounts/{id}', function ($id) {
  $accounts = Accounts::find($id);

  if ($accounts) {
    $accountsArr = [
      "id" => $accounts->account_id,
      "email" => $accounts->email,
      "type" => $accounts->type,
      "first_login" => $accounts->first_login,
      "status" => $accounts->status,
  ];
    return response()->json($accountsArr);
  } else {
    return response()->json(["message" => "User ID not found"], 404);
  }
});

Route::get('/TDM/accounts', function () {
  $accounts = Accounts::all();

  if ($accounts) {
    $accountsArr = array();

    foreach ($accounts as $value) {
      $tempArr = array(
        "id" => $value->account_id,
        "email" => $value->email,
        "type" => $value->type,
        "first_login" => $value->first_login,
        "status" => $value->status,
      );
      array_push($accountsArr, $tempArr);
    }
    return response()->json($accountsArr);
  } else {
    return response()->json(["meesage" => "Users not found"], 404);
  }
});

Route::post('/TDM/accounts/create', function (Request $request) {
  $validator = Validator::make($request->all(), [
      'email' => 'required|email|ends_with:adamson.edu.ph|unique:accounts,email',
      'type' => 'required',
  ], [
      'email.required' => 'Please enter an Adamson email address.',
      'email.ends_with' => 'Please enter an Adamson email address.',
      'email.email' => 'Please enter a valid email address.',
      'email.unique' => 'The email is already in use',
      'type.required' => 'Please choose a user level.',
  ]);

  if ($validator->fails()) {
      return response()->json(["message" => "Validation failed", "errors" => $validator->errors()], 400);
  }

  $randomPassword = Str::random(8);
  $accounts = Accounts::create([
      'email' => $request->input('email'),
      'default_password' => $randomPassword,
      'type' => $request->input('type'),
      'first_login' => 'true'
  ]);

  if ($accounts) {
    $accountsArr = [
      "id" => $accounts->account_id,
      "email" => $accounts->email,
      "type" => $accounts->type,
      "first_login" => $accounts->first_login,
      "status" => $accounts->status,
  ];
    return response()->json($accountsArr);
  } else {
    return response()->json(["message" => "User ID not found"], 404);
  }
});


Route::post('/user/new', function (Request $request) {
  $validator = Validator::make($request->all(), [
  'name' => 'required|name',
  'email' => 'required|email|ends_with:adamson.edu.ph|unique:user,email',
  ], 
      [
          'email.required' => 'Please enter an Adamson email address.',
          'email.ends_with' => 'Please enter an Adamson email address.',
          'email.email' => 'Please enter a valid email address.',
          'email.unique' => 'The email is already in use',
      ]);
  if ($validator->fails()) {
      return response()->json(["message" => "Validation failed", "errors" => $validator->errors()], 400);
  }

  $randomPassword = Str::random(8);

  $user = Accounts::create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => $randomPassword
  ]);

  if ($user) {
      $userArr = [
          "id" => $user->account_id,
          "name" => $user->name,
          "email" => $user->email,
          "created_at" => $user->created_at,
          "updated_at" => $user->updated_at,
      ];
      return response()->json($userArr);
  } else {
      return response()->json(["message" => "User ID not found"], 404);
  }
});
