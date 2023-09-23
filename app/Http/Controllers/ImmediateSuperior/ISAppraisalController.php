<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\KRA;
use App\Models\WPP;
use App\Models\LDP;
use App\Models\JIC;
use App\Models\Appraisals;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ISAppraisalController extends Controller
{
  public function viewAppraisal($appraisal_id)
  {
    if (session()->has('account_id')) {
      // Retrieve the appraisal records for the given employee_id and evaluator_id
      $appraisals = Appraisals::where('appraisal_id', $appraisal_id)->get();

      // If no appraisal record is found for the given employee and evaluator, handle the error
      if ($appraisals->isEmpty()) {
        // Handle the case where appraisal data is not found
        // You may want to display an error message or redirect to a 404 page
      }

      // Initialize variables for appraisee and evaluator data
      $appraisee = null;
      $evaluator = null;
      $appraisal_Id = null;

      // Loop through the appraisal records to find the correct appraisal type and evaluator data
      foreach ($appraisals as $appraisal) {
        // Fetch the appraisee data based on the $employee_id
        $appraisee = Employees::find($appraisal->employee_id);
        // Determine the appraisal type
        $appraisalType = $appraisal->evaluation_type;

        // Handle different appraisal types
        if ($appraisalType === 'self evaluation') {
          $evaluator = $appraisee;
          $appraisal_Id = $appraisal->appraisal_id;
        } elseif ($appraisalType === 'internal customer 1' || $appraisalType === 'internal customer 2') {
          $evaluator = Employees::find($appraisal->evaluator_id);
          $appraisal_Id = $appraisal->appraisal_id;
        } elseif ($appraisalType === 'is evaluation') {
          $evaluator = Employees::find($appraisal->evaluator_id);
          $appraisal_Id = $appraisal->appraisal_id;
        }
        break; // Exit the loop after finding the first matching appraisal
      }

      // Return the view with appraisee, evaluator, and appraisal ID data
      return view('is-pages.is_appraisal', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisal_id');
    $kraData = KRA::where('appraisal_id', $appraisalId)->get();
    $wpaData = WPP::where('appraisal_id', $appraisalId)->get();
    $ldpData = LDP::where('appraisal_id', $appraisalId)->get();

    return response()->json(['success' => true, 'kraData' => $kraData, 'wpaData' => $wpaData, 'ldpData' => $ldpData]);
  }

  public function saveISAppraisal(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $validator = $this->validateISAppraisal($request);

    if ($validator->fails()) {
      // Display validation errors using dd()
      dd($validator->errors());

      //return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
      /*            
      $this->createSID($request);
      $this->createSR($request);
      $this->createS($request);
      */

      $this->createKRA($request);

      /*
      $this->createWPA($request);
      $this->createLDP($request);
      */

      DB::commit();
      return redirect()->route('viewISAppraisalsOverview')->with('success', 'Submition Complete!');
    } catch (\Exception $e) {
      DB::rollBack();

      // Display exception details using dd()
      dd('An error occurred while saving data. Line: ' . $e->getLine(), $e->getMessage(), $e->getTraceAsString());

      // return redirect()->back()->with('error', 'An error occurred while saving data.');
    }

  }

  protected function validateISAppraisal(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    return Validator::make($request->all(), [
      'appraisalID' => 'required|numeric',
      /*
      'SID' => 'required|array',
      'SID.*' => 'required|array',
      'SID.*.*.SIDanswer' => 'required',

      'SR' => 'required|array',
      'SR.*' => 'required|array',
      'SR.*.*.SRanswer' => 'required',

      'S' => 'required|array',
      'S.*' => 'required|array',
      'S.*.*.Sanswer' => 'required',
      */
      'KRA' => 'required|array',
      'KRA.*' => 'required|array',
      'KRA.*.*.kraID' => 'required|numeric',
      'KRA.*.*.KRA' => 'required|string',
      'KRA.*.*.KRA_weight' => 'required|numeric',
      'KRA.*.*.KRA_objective' => 'required|string',
      'KRA.*.*.KRA_performance_indicator' => 'required|string',
      /*
      'WPA' => 'required|array',
      'WPA.*' => 'required|array',
      'WPA.*.*.continue_doing' => 'required|string',
      'WPA.*.*.stop_doing' => 'required|string',
      'WPA.*.*.start_doing' => 'required|string',

      'LDP' => 'required|array',
      'LDP.*' => 'required|array',
      'LDP.*.*.learning_need' => 'required|string',
      'LDP.*.*.methodology' => 'required|string',
      
      'feedback.*.question' => 'required|string',
      'feedback.*.answer' => 'required|numeric',
      'feedback.*.comment' => 'required|string',
      */
    ], [
      // Custom error messages
    ]);
  }

  protected function createSID(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('SID') as $questionId => $questionData) {
      $score = $questionData[$request->input('appraisalID')]['SIDanswer'];

      $existingRecord = AppraisalAnswers::where('appraisal_id', $request->input('appraisalID'))
        ->where('question_id', $questionId)
        ->first();

      if ($existingRecord) {
        // Update the record if the score is different
        if ($existingRecord->score != $score) {
          $existingRecord->update([
            'score' => $score,
          ]);
        }
      } else {
        // Create a new record if no existing record is found
        AppraisalAnswers::create([
          'appraisal_id' => $request->input('appraisalID'),
          'question_id' => $questionId,
          'score' => $score,
        ]);
      }
    }
  }


  protected function createSR(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('SR') as $questionId => $questionData) {
      $score = $questionData[$request->input('appraisalID')]['SRanswer'];

      // Check if an existing record with the same appraisal_id and question_id exists
      $existingRecord = AppraisalAnswers::where('appraisal_id', $request->input('appraisalID'))
        ->where('question_id', $questionId)
        ->first();

      if ($existingRecord) {
        // Update the record if the score is different
        if ($existingRecord->score != $score) {
          $existingRecord->update([
            'score' => $score,
          ]);
        }
      } else {
        // Create a new record if no existing record is found
        AppraisalAnswers::create([
          'appraisal_id' => $request->input('appraisalID'),
          'question_id' => $questionId,
          'score' => $score,
        ]);
      }
    }
  }

  protected function createS(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('S') as $questionId => $questionData) {
      $score = $questionData[$request->input('appraisalID')]['Sanswer'];

      // Check if an existing record with the same appraisal_id and question_id exists
      $existingRecord = AppraisalAnswers::where('appraisal_id', $request->input('appraisalID'))
        ->where('question_id', $questionId)
        ->first();

      if ($existingRecord) {
        if ($existingRecord->score != $score) {
          $existingRecord->update([
            'score' => $score,
          ]);
        }
      } else {
        AppraisalAnswers::create([
          'appraisal_id' => $request->input('appraisalID'),
          'question_id' => $questionId,
          'score' => $score,
        ]);
      }
    }
  }

  protected function createKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('KRA') as $kraID => $kraData) {
      $existingKRA = KRA::where('appraisal_id', $request->input('appraisalID'))
        ->where('kra_id', $kraID)
        ->first();

      if ($existingKRA) {
        if (
          $existingKRA->kra !== $kraData[$request->input('appraisalID')]['KRA'] ||
          $existingKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_weight'] ||
          $existingKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
          $existingKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator']
        ) {
          $existingKRA->update([
            'kra' => $kraData[$request->input('appraisalID')]['KRA'],
            'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
            'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
            'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          ]);
        }
      } else {
        KRA::create([
          'appraisal_id' => $request->input('appraisalID'),
          'kra' => $kraData[$request->input('appraisalID')]['KRA'],
          'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
          'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
          'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          'kra_order' => $kraID,
        ]);
      }

      $existingSelfEvalKRA = KRA::where('appraisal_id', $request->input('appraisalID') - 1)
        ->where('kra_id', $kraID + 1)
        ->first();

      if ($existingSelfEvalKRA) {
        if (
          $existingSelfEvalKRA->kra !== $kraData[$request->input('appraisalID')]['KRA'] ||
          $existingSelfEvalKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_weight'] ||
          $existingSelfEvalKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
          $existingSelfEvalKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator']
        ) {
          $existingSelfEvalKRA->update([
            'kra' => $kraData[$request->input('appraisalID')]['KRA'],
            'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
            'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
            'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          ]);
        }
      } else {
        KRA::create([
          'appraisal_id' => $request->input('appraisalID') - 1,
          'kra' => $kraData[$request->input('appraisalID')]['KRA'],
          'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
          'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
          'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          'kra_order' => $kraID,
        ]);
      }

    }
  }

  protected function createWPA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('WPA') as $wpaID => $wppData) {
      $existingWPP = WPP::where('appraisal_id', $request->input('appraisalID'))
        ->where('performance_plan_id', $wpaID)
        ->first();

      if ($existingWPP) {
        if (
          $existingWPP->continue_doing !== $wppData[$request->input('appraisalID')]['continue_doing'] ||
          $existingWPP->stop_doing !== $wppData[$request->input('appraisalID')]['stop_doing'] ||
          $existingWPP->start_doing !== $wppData[$request->input('appraisalID')]['start_doing']
        ) {
          $existingWPP->update([
            'continue_doing' => $wppData[$request->input('appraisalID')]['continue_doing'],
            'stop_doing' => $wppData[$request->input('appraisalID')]['stop_doing'],
            'start_doing' => $wppData[$request->input('appraisalID')]['start_doing'],
          ]);
        }
      } else {
        WPP::create([
          'appraisal_id' => $request->input('appraisalID'),
          'continue_doing' => $wppData[$request->input('appraisalID')]['continue_doing'],
          'stop_doing' => $wppData[$request->input('appraisalID')]['stop_doing'],
          'start_doing' => $wppData[$request->input('appraisalID')]['start_doing'],
          'performance_plan_order' => $wpaID
        ]);
      }
    }
  }

  protected function createLDP(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('LDP') as $ldpID => $ldpData) {
      $existingLDP = LDP::where('appraisal_id', $request->input('appraisalID'))
        ->where('development_plan_id', $ldpID)
        ->first();

      if ($existingLDP) {
        if (
          $existingLDP->learning_need !== $ldpData[$request->input('appraisalID')]['learning_need'] ||
          $existingLDP->methodology !== $ldpData[$request->input('appraisalID')]['methodology']
        ) {
          $existingLDP->update([
            'learning_need' => $ldpData[$request->input('appraisalID')]['learning_need'],
            'methodology' => $ldpData[$request->input('appraisalID')]['methodology'],
          ]);
        }
      } else {
        LDP::create([
          'appraisal_id' => $request->input('appraisalID'),
          'learning_need' => $ldpData[$request->input('appraisalID')]['learning_need'],
          'methodology' => $ldpData[$request->input('appraisalID')]['methodology'],
          'development_plan_order' => $ldpID
        ]);
      }
    }
  }

  protected function createJIC(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $JICData = $request->input('feedback');
    foreach ($JICData as $questionNumber => $data) {
      JIC::create([
        'appraisal_id' => $request->input('appraisalID'),
        'job_incumbent_question' => $data['question'],
        'answer' => $data['answer'],
        'comments' => $data['comment'],
      ]);
    }
  }

  public function getAppraisalSE($employee_id)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $evaluatee = Appraisals::where('employee_id', $employee_id)
      ->where('evaluator_id', $employee_id)
      ->with(['employee.department', 'employee.immediateSuperior'])
      ->first();

    $firstName = $evaluatee->employee->first_name;
    $lastName = $evaluatee->employee->last_name;
    $jobTitle = $evaluatee->employee->job_title;

    $department = $evaluatee->employee->department;
    $appraisalId = $evaluatee->appraisal_id;

    // Access the immediate superior's details (if available)
    $immediateSuperior = $evaluatee->employee->immediateSuperior;
    $immediateSuperiorName = $immediateSuperior ? ($immediateSuperior->first_name . ' ' . $immediateSuperior->last_name) : null;
    $immediateSuperiorPosition = $immediateSuperior ? $immediateSuperior->position : null;

    $evaluater = Appraisals::where('evaluator_id', $employee_id)->first();

    $data = [
      'success' => true,
      'evaluatee' => $evaluatee,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'job_title' => $jobTitle,
      'department' => $department,
      'immediate_superior_name' => $immediateSuperiorName,
      'immediate_superior_position' => $immediateSuperiorPosition,
      'appraisal_id' => $appraisalId,

      'evaluater' => $evaluater,
    ];

    return view('is-pages.is_appraisal', $data);
  }

  public function deleteKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $kraID = $request->input('kraID');

    // Perform the actual deletion of the KRA record from the database
    try {
      KRA::where('kra_id', $kraID)->delete();
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error deleting KRA record.']);
    }
  }
  public function deleteWPA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $wpaID = $request->input('wpaID');

    // Perform the actual deletion of the WPA record from the database
    try {
      WPP::where('performance_plan_id', $wpaID)->delete();
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error deleting WPA record.']);
    }
  }
  public function deleteLDP(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    
    $ldpID = $request->input('ldpID');

    // Perform the actual deletion of the WPA record from the database
    try {
      WPP::where('development_plan_id', $ldpID)->delete();
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error deleting LDP record.']);
    }
  }

}
?>