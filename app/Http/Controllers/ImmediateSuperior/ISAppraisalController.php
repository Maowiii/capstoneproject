<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\KRA;
use App\Models\Signature;
use App\Models\WPP;
use App\Models\LDP;
use App\Models\JIC;
use App\Models\Appraisals;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

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
    $jicData = JIC::where('appraisal_id', $appraisalId)->get();
    $signData = Signature::where('appraisal_id', $appraisalId)->get();

    return response()->json(['success' => true, 'kraData' => $kraData, 'wpaData' => $wpaData, 'ldpData' => $ldpData, 'jicData' => $jicData, 'signData' => $signData]);
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
                  
      $this->createSID($request);
      $this->createSR($request);
      $this->createS($request);
      $this->createKRA($request);
      $this->createWPA($request);
      $this->createLDP($request);
      $this->createJIC($request);

      $this->createSign($request);

      $appraisalID = $request->input('appraisalID');
      $existingRecord = Appraisals::where('appraisal_id', $appraisalID)
        ->first();

      $date = Carbon::now();
      $getBHave = AppraisalAnswers::where('appraisal_id', $appraisalID)->average('score');
      $getKRAave = KRA::where('appraisal_id', $appraisalID)->sum('weighted_total');

      $existingRecord->update([
        'date_submitted' => $date,
        'bh_score' => $getBHave,
        'kra_score' => $getKRAave,
      ]);

      $employee_id = $existingRecord->employee_id;
      $departmentId = $existingRecord->department_id;

      $appraisalData = Appraisals::where('employee_id', $employee_id)->get(); // Get all appraisals for the employee
      // Check if all appraisals have a non-null date_submitted
      $allSubmitted = $appraisalData->every(function ($appraisal) {
        return $appraisal->date_submitted !== null;
      });

      if ($allSubmitted) {
        $finalScore = $this->calculateFinalScore($appraisalData);
        FinalScores::updateOrCreate(
          [
            'employee_id' => $employee_id,
            'department_id' => $departmentId,
          ],
          ['final_score' => $finalScore[0]]
        );
      }
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
      
      'SID' => 'required|array',
      'SID.*' => 'required|array',
      'SID.*.*.SIDanswer' => 'required',

      'SR' => 'required|array',
      'SR.*' => 'required|array',
      'SR.*.*.SRanswer' => 'required',

      'S' => 'required|array',
      'S.*' => 'required|array',
      'S.*.*.Sanswer' => 'required',
      
      'KRA' => 'required|array',
      'KRA.*' => 'required|array',
      'KRA.*.*.kraID' => 'required|numeric',
      'KRA.*.*.KRA_kra' => 'required|string',
      'KRA.*.*.KRA_kra_weight' => 'required|numeric',
      'KRA.*.*.KRA_objective' => 'required|string',
      'KRA.*.*.KRA_performance_indicator' => 'required|string',
      'KRA.*.*.KRA_actual_result' => 'required|string',
      'KRA.*.*.KRA_performance_level' => 'required|numeric',
      'KRA.*.*.KRA_weighted_total' => 'required|numeric',
      
      'WPA' => 'required|array',
      'WPA.*' => 'required|array',
      'WPA.*.*.continue_doing' => 'required|string',
      'WPA.*.*.stop_doing' => 'required|string',
      'WPA.*.*.start_doing' => 'required|string',

      'LDP' => 'required|array',
      'LDP.*' => 'required|array',
      'LDP.*.*.learning_need' => 'required|string',
      'LDP.*.*.methodology' => 'required|string',
      
      'feedback' => 'required|array',
      'feedback.*' => 'required|array',
      'feedback.*.*.question' => 'required|string',
      'feedback.*.*.answer' => 'required|numeric',
      'feedback.*.*.comments' => 'required|string',
    ], [
      // Custom error messages
    ]);
  }

  public function autosaveKRAField(Request $request)
{
    // Retrieve the data sent from the frontend
    $appraisalId = $request->input('appraisalId');
    $kraID = $request->input('kraID');
    $fieldName = $request->input('fieldName');
    $fieldValue = $request->input('fieldValue');
    Log::info($appraisalId);
    Log::info($fieldName);
    Log::info($fieldValue);

    try {
      // Check if the field name requires updates with both appraisal IDs
      $requiresDoubleUpdate = in_array($fieldName, ['kra', 'kra_weight', 'objective', 'performance_indicator']);
  
      // Determine which appraisal IDs to use based on the field name
      $appraisalIds = $requiresDoubleUpdate ? [$appraisalId - 1, $appraisalId] : [$appraisalId];
      $KRAIds = $requiresDoubleUpdate ? [$kraID - 1, $kraID] : [$kraID];
  
      $count = 0; // Initialize count
  
      // Iterate through the appraisal IDs and update/create records
      foreach ($appraisalIds as $id) {
          // Find the KRA by appraisal ID, kraID, and fieldName
          $kra = KRA::where('appraisal_id', $id)
                    ->where('kra_id', $KRAIds[$count]) // Use the count variable to access KRA ID
                    ->first();
  
          if ($kra) {
              // Update the specific field value
              $kra->$fieldName = $fieldValue;
          } else {
              // Create a new KRA record
              $kra = new KRA([
                  'appraisal_id' => $id,
                  'kra_id' => $KRAIds[$count], // Use the count variable
                  'kra_order' => $kraID,
                  $fieldName => $fieldValue,
              ]);
          }
          // Save the KRA record
          $kra->save();
          
          $count++;
      }
  
      return response()->json(['message' => 'KRA created and autosave successful', 'kraData' => $kra]);
  } catch (\Exception $e) {
      // Handle errors if any
      return response()->json(['error' => 'Autosave failed'], 500);
  }
}

  public function autosaveWPPField(Request $request)
  {
    // Retrieve the data sent from the frontend
    $wppID = $request->input('wppID');
    $fieldName = $request->input('fieldName');
    $fieldValue = $request->input('fieldValue');
    $appraisalId = $request->input('appraisalId');

    Log::info($fieldName);
    Log::info($fieldValue);
    try {
      // Find the existing record based on the criteria
      $wpp = WPP::where([
        'performance_plan_id' => $wppID,
        'appraisal_id' => $appraisalId,
      ])->first();

      // If the record exists, update the specific field value; otherwise, create a new record
      if ($wpp) {
        $wpp->$fieldName = $fieldValue;
        $wpp->save();
      } else {
        // Create a new record with the criteria and the specific field value
        $wpp = new WPP([
          'performance_plan_id' => $wppID,
          'appraisal_id' => $appraisalId,
          'performance_plan_order' => $wppID,
          $fieldName => $fieldValue
        ]);
        $wpp->save();
      }
      Log::info($wpp);

      // Return the ID in the response
      return response()->json(['message' => 'Autosave successful', 'wpaData' => $wpp]);
    } catch (\Exception $e) {
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      // Handle errors if any
      return response()->json(['error' => 'Autosave failed'], 500);
    }
  }

  public function autosaveLDPField(Request $request)
  {
      // Retrieve the data sent from the frontend
      $ldpID = $request->input('ldpID');
      $fieldName = $request->input('fieldName');
      $fieldValue = $request->input('fieldValue');
      $appraisalId = $request->input('appraisalId');

      try {
          // Find the existing record based on the criteria
          $ldp = LDP::where([
              'development_plan_id' => $ldpID,
              'appraisal_id' => $appraisalId,
          ])->first();

          // If the record exists, update the specific field value; otherwise, create a new record
          if ($ldp) {
              $ldp->$fieldName = $fieldValue;
              $ldp->save();
          } else {
              // Create a new record with the criteria and the specific field value
              $ldp = new LDP([
                  'development_plan_id' => $ldpID, // Use 'development_plan_id' here
                  'appraisal_id' => $appraisalId,
                  'development_plan_order' => $ldpID,
                  $fieldName => $fieldValue
              ]);
              $ldp->save();
          }

          // Return the ID in the response
          return response()->json(['message' => 'Autosave successful', 'ldpData' => $ldp]);
      } catch (\Exception $e) {
          Log::error('Exception Message: ' . $e->getMessage());
          Log::error('Exception Line: ' . $e->getLine());
          Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

          // Handle errors if any
          return response()->json(['error' => 'Autosave failed'], 500);
      }
  }

  public function autosaveJICField(Request $request)
  {
    // Retrieve the data sent from the frontend
    $jicID = $request->input('jicID');
    $fieldName = $request->input('fieldName');
    $fieldValue = $request->input('fieldValue');
    $fieldQuestion = $request->input('fieldQuestion');
    $appraisalId = $request->input('appraisalId');

    try {
      // Find the existing record based on the criteria
      $jic = JIC::where([
        'question_order' => $jicID,
        'appraisal_id' => $appraisalId,
      ])->first();

      // If the record exists, update the specific field value; otherwise, create a new record
      if ($jic) {
        $jic->$fieldName = $fieldValue;
        $jic->job_incumbent_question = $fieldQuestion;
        $jic->save();
      } else {
        // Create a new record with the criteria and the specific field value
        $jic = new JIC([
          'appraisal_id' => $appraisalId,
          'question_order' => $jicID,
          'job_incumbent_question' => $fieldQuestion,
          $fieldName => $fieldValue
        ]);
        $jic->save();
      }

      // Return the ID in the response
      return response()->json(['message' => 'Autosave successful']);
    } catch (\Exception $e) {
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      // Handle errors if any
      return response()->json(['error' => 'Autosave failed'], 500);
    }
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
    foreach ($request->input('KRA') as $kraID => $kraData) {
      $existingKRA = KRA::where('appraisal_id', $request->input('appraisalID'))
        ->where('kra_id', $kraID)
        ->first();
      if ($existingKRA) {
        if (
          $existingKRA->kra !== $kraData[$request->input('appraisalID')]['KRA_kra'] ||
          $existingKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_kra_weight'] ||
          $existingKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
          $existingKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator']
        ) {
          $existingKRA->update([
            'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
            'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
            'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
            'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          ]);
        }
      } else {
        KRA::create([
          'appraisal_id' => $request->input('appraisalID'),
          'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
          'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
          'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
          'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          'kra_order' => $kraID,
        ]);
      }
      $existingSelfEvalKRA = KRA::where('appraisal_id', $request->input('appraisalID') - 1)
        ->where('kra_id', $kraID + 1)
        ->first();
      if ($existingSelfEvalKRA) {
        Log::info($existingSelfEvalKRA);
        Log::info('KRA ID: ' . ($kraID - 1)); // Use parentheses for subtraction
        Log::info('Appraisal ID ' . ($request->input('appraisalID') - 1)); // Use parentheses for subtraction
        if (
          $existingSelfEvalKRA->kra !== $kraData[$request->input('appraisalID')]['KRA_kra'] ||
          $existingSelfEvalKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_kra_weight'] ||
          $existingSelfEvalKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
          $existingSelfEvalKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator']
        ) {
          $existingSelfEvalKRA->update([
            'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
            'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
            'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
            'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          ]);
        }
      } else {
        Log::info('No matching KRA found.');
        Log::info('KRA ID: ' . ($kraID - 1)); // Use parentheses for subtraction
        Log::info('Appraisal ID ' . ($request->input('appraisalID') - 1)); // Use parentheses for subtraction
        KRA::create([
          'appraisal_id' => $request->input('appraisalID') - 1,
          'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
          'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
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

    foreach ($request->input('feedback') as $jicID => $jicData) {
      $existingJIC = JIC::where('appraisal_id', $request->input('appraisalID'))
        ->where('job_incumbent_id', $jicID)
        ->first();

      if ($existingJIC) {
        if (
          $existingJIC->job_incumbent_question !== $jicData[$request->input('appraisalID')]['question'] ||
          $existingJIC->answer !== $jicData[$request->input('appraisalID')]['answer'] ||
          $existingJIC->comments !== $jicData[$request->input('appraisalID')]['comment']
        ) {
          $existingJIC->update([
            'job_incumbent_question' => $jicData[$request->input('appraisalID')]['question'],
            'answer' => $jicData[$request->input('appraisalID')]['answer'],
            'comments' => $jicData[$request->input('appraisalID')]['comments'],
          ]);
        }
      } else {
        JIC::create([
          'appraisal_id' => $request->input('appraisalID'),
          'job_incumbent_question' => $jicData[$request->input('appraisalID')]['question'],
          'answer' => $jicData[$request->input('appraisalID')]['answer'],
          'comments' => $jicData[$request->input('appraisalID')]['comments'],
          'question_order' => $jicID
        ]);
      }
    }
  }

  protected function createSign(Request $request)
  {
      if (!session()->has('account_id')) {
          return view('auth.login');
      }

      $appraisalId = $request->input('appraisalID');
      $signatureData = $request->input('SIGN.JI.' . $appraisalId);

      // Check if the signature data exists and is not empty
      if (!empty($signatureData)) {
          // Try to find an existing signature
          $existingSignature = Signature::where('appraisal_id', $appraisalId)
              ->where('sign_type', 'IS')
              ->first();

          if ($existingSignature) {
              // Update the existing signature data
              $existingSignature->update([
                  'sign_data' => $signatureData,
                  'sign_type' => 'IS',
              ]);
          } else {
              // Create a new signature if it doesn't exist
              try {
                  // Create a new signature and retrieve its ID
                  $newSignature = Signature::create([
                      'appraisal_id' => $appraisalId,
                      'sign_data' => $signatureData,
                      'sign_type' => 'IS',
                  ]);

                  // Get the ID of the newly created signature
                  $newSignatureId = $newSignature->signature_id;
              } catch (\Exception $e) {
                  // Handle the database connection issue
                  // You can log the error or display a user-friendly message
                  Log::error('Exception Message: ' . $e->getMessage());
                  Log::error('Exception Line: ' . $e->getLine());
                  Log::error('Exception Stack Trace: ' . $e->getTraceAsString());
              }
          }
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
    Log::info('$ldpID: ' . $ldpID);
    // Perform the actual deletion of the WPA record from the database
    try {
      LDP::where('development_plan_id', $ldpID)->delete();
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      return response()->json(['success' => false, 'message' => 'Error deleting LDP record.']);
    }
  }

  function calculateFinalScore($appraisals)
  {
    $behavioralCompetenciesGrade = 0;
    $kraGrade = 0;
    $kraFS = 0;
    $finalGrade = null; // Initialize the finalGrade to null
    $allSubmitted = false;
    $kraFormsCount = 0;

    // Log initial information
    Log::info('Starting final score calculation');

    foreach ($appraisals as $appraisal) {
      $evaluationType = $appraisal['evaluation_type'];
      $bhScore = $appraisal['bh_score'];
      $kraScore = $appraisal['kra_score'];
      $icScore = $appraisal['ic_score'];

      // Log information for the current appraisal
      Log::info('Processing appraisal for evaluation type: ' . $evaluationType);
      Log::info('BH Score: ' . $bhScore);
      Log::info('KRA Score: ' . $kraScore);
      Log::info('IC Score: ' . $icScore);

      // Check if date_submitted is not null
      if ($appraisal['date_submitted'] !== null) {
        // Calculate the behavioral competencies grade
        if ($evaluationType === 'self evaluation') {
          $behavioralCompetenciesGrade += ($bhScore * 0.1);
        } elseif ($evaluationType === 'is evaluation') {
          $behavioralCompetenciesGrade += ($bhScore * 0.5);
        } elseif ($evaluationType === 'internal customer 1' || $evaluationType === 'internal customer 2') {
          $behavioralCompetenciesGrade += ($icScore * 0.2);
        }

        // Calculate the KRA grade for 'self evaluation' and 'is evaluation'
        if ($evaluationType === 'self evaluation' || $evaluationType === 'is evaluation') {
          $kraGrade += $kraScore;
          $kraFormsCount++;
        }

        $allSubmitted = true;
      } else {
        // Set the finalGrade to null and break out of the loop
        $allSubmitted = false;
        $finalGrade = null;
        $kraFormsCount = null;

        break;
      }
    }

    // Check if the loop completed successfully (date_submitted was not null for all appraisals)
    if ($allSubmitted) {
      $kraFS = $kraGrade / $kraFormsCount; // Calculate the average KRA grade
      $finalGrade = ($behavioralCompetenciesGrade * 0.4) + ($kraFS * 0.6);

      // Log the final grade
      Log::info('Final Grade Calculation:');
      Log::info('Behavioral Competencies Grade: ' . $behavioralCompetenciesGrade);
      Log::info('KRA Grade: ' . $kraGrade);

      Log::info('KRA Final Score: ' . $kraFS);
      Log::info('Final Grade Computation: (' . $behavioralCompetenciesGrade . ' x 40%)  + (' . $kraFS . ' x 60%)');
      Log::info('Final Grade: ' . $finalGrade);
    } else {
      // Log that the loop did not complete successfully
      Log::info('Final Grade calculation skipped due to date_submitted being null for an appraisal.');
    }

    // Log the complete result
    Log::info('Final Score Calculation Complete');

    return [$finalGrade, $behavioralCompetenciesGrade, $kraFS];
  }

  public function calculateStatus($appraisals)
  {
    // Initialize status as "complete"
    $status = 'Complete';

    foreach ($appraisals as $appraisal) {
      if ($appraisal->date_submitted === null) {
        // If any appraisal has a null date_submitted, set status to "pending"
        $status = 'Pending';
        break; // No need to continue checking, status is already "pending"
      }
    }
    return $status;
  }
}
?>