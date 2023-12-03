<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\AppraisalAnswers;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\KRA;
use App\Models\ScoreWeights;
use App\Models\WPP;
use App\Models\LDP;
use App\Models\JIC;
use App\Models\Signature;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\FormQuestions;
use App\Models\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class SelfEvaluationController extends Controller
{
  public function displaySelfEvaluationForm()
  {
    if (session()->has('account_id')) {
      return view('pe-pages.pe_self_evaluation');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }

  public function getQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisal_id');
    $selectedYear = $request->input('sy');
    
    if (is_null($selectedYear) || $selectedYear == null || $selectedYear == "null") {
      $SID = FormQuestions::where('table_initials', 'SID')->where('status', 'active')->get();
      $SR = FormQuestions::where('table_initials', 'SR')->where('status', 'active')->get();
      $S = FormQuestions::where('table_initials', 'S')->where('status', 'active')->get();

      // Retrieve stored scores for each question ID
      $storedValues = AppraisalAnswers::where('appraisal_id', $appraisalId)
        ->pluck('score', 'question_id') 
        ->toArray();
    }else{
      $FormQuestionstable = 'form_questions_' . $selectedYear;
      $AppraisalAnswerstable = 'appraisal_answers_' . $selectedYear;

      $SID = FormQuestions::from($FormQuestionstable)->where('table_initials', 'SID')->where('status', 'active')->get();
      $SR = FormQuestions::from($FormQuestionstable)->where('table_initials', 'SR')->where('status', 'active')->get();
      $S = FormQuestions::from($FormQuestionstable)->where('table_initials', 'S')->where('status', 'active')->get();

      // Retrieve stored scores for each question ID
      $storedValues = AppraisalAnswers::from($AppraisalAnswerstable)->where('appraisal_id', $appraisalId)
        ->pluck('score', 'question_id') 
        ->toArray();
    }
    
    // Merge stored values with question data
    $SID->each(function ($question) use ($storedValues) {
      $question->score = $storedValues[$question->question_id] ?? null;
    });

    $SR->each(function ($question) use ($storedValues) {
      $question->score = $storedValues[$question->question_id] ?? null;
    });

    $S->each(function ($question) use ($storedValues) {
      $question->score = $storedValues[$question->question_id] ?? null;
    });

    $data = [
      'success' => true,
      'SID' => $SID,
      'SR' => $SR,
      'S' => $S
    ];

    return response()->json($data);
  }

  public function getPEKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisal_id');
    $selectedYear = $request->input('sy');

    if (is_null($selectedYear) || $selectedYear == null || $selectedYear == "null") {
      $eulaData = Appraisals::where('appraisal_id', $appraisalId)->pluck('eula');
      $kraData = KRA::where('appraisal_id', $appraisalId)->get();
      $wpaData = WPP::where('appraisal_id', $appraisalId)->get();
      $ldpData = LDP::where('appraisal_id', $appraisalId)->get();
      $jicData = JIC::where('appraisal_id', $appraisalId)->get();
      $signData = Signature::where('appraisal_id', $appraisalId)
        ->with('appraisal.evaluator:employee_id,first_name,last_name')
        ->get();
    }else{
      $Appraisals_table = 'appraisals_' . $selectedYear;
      $KRA_table = 'kras_' . $selectedYear;
      $WPP_table = 'work_performance_plans_' . $selectedYear;
      $LDP_table = 'learning_development_plans_' . $selectedYear;
      $JIC_table = 'job_incumbents_' . $selectedYear;
      $Signature_table = 'signature_' . $selectedYear;
      Log::info($Signature_table);
      $eulaData = Appraisals::from($Appraisals_table)->where('appraisal_id', $appraisalId)->pluck('eula');
      $kraData = KRA::from($KRA_table)->where('appraisal_id', $appraisalId)->get();
      $wpaData = WPP::from($WPP_table)->where('appraisal_id', $appraisalId)->get();
      $ldpData = LDP::from($LDP_table)->where('appraisal_id', $appraisalId)->get();
      $jicData = JIC::from($JIC_table)->where('appraisal_id', $appraisalId)->get();
      $signData = Signature::from($Signature_table)->where('appraisal_id', $appraisalId)
        ->with('appraisal.evaluator:employee_id,first_name,last_name')
        ->get();
    }
    
    return response()->json(['success' => true, 'eulaData' => $eulaData, 'kraData' => $kraData, 'wpaData' => $wpaData, 'ldpData' => $ldpData, 'jicData' => $jicData, 'signData' => $signData]);
  }

  public function showAppraisalForm(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisal_id');

    $kraData = KRA::where('appraisal_id', $appraisalId)->get();

    // Return the KRA data as a JSON response
    return response()->json(['success' => true, 'isAppraisalData' => $kraData]);
  }

  public function getData(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $account_id = session()->get('account_id');
    $user = Employees::where('account_id', $account_id)->first();

    if (!$user) {
      throw new \Exception('User not found.');
    }

    $appraisee = Employees::where('account_id', $account_id)->get();

    $appraisals = Appraisals::where('employee_id', $user->employee_id)
      ->with('evaluator', 'employee')
      ->get();

    $status = $this->calculateStatus($appraisals);

    // Calculate the final score using the PHP function
    $FinalScores = FinalScores::where('employee_id', $user->employee_id)->pluck('final_score');

    $data = [
      'success' => true,
      'appraisee' => $appraisee,
      'appraisals' => $appraisals,
      'is' => $user,
      'status' => $status,
      'final_score' => $FinalScores,
    ];

    return response()->json($data);
  }


  public function viewAppraisal($appraisal_id)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisals = Appraisals::where('appraisal_id', $appraisal_id)->get();

    // Initialize variables for appraisee and evaluator data
    $appraisee = null;
    $evaluator = null;
    $appraisal_Id = null;

    // Loop through the appraisal records to find the correct appraisal type and evaluator data
    foreach ($appraisals as $appraisal) {
      $appraisee = Employees::find($appraisal->employee_id);
      $appraisalType = $appraisal->evaluation_type;

      // Handle different appraisal types
      if ($appraisalType === 'self evaluation') {
        $evaluator = $appraisee;
        $appraisal_Id = $appraisal->appraisal_id;
        return view('pe-pages.pe_self_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
      } elseif ($appraisalType === 'internal customer 1') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
        return view('pe-pages.pe_ic_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
      } elseif ($appraisalType === 'internal customer 2') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
        return view('pe-pages.pe_ic_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
      } elseif ($appraisalType === 'is evaluation') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
        return view('is-pages.is_appraisal', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
      }
      break;
    }
  }

  public function viewGOAppraisal($appraisal_id)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    // Retrieve the appraisal records for the given employee_id and evaluator_id
    $appraisals = Appraisals::where('appraisal_id', $appraisal_id)->get();

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
      } elseif ($appraisalType === 'internal customer 1') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
      } elseif ($appraisalType === 'internal customer 2') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
      } elseif ($appraisalType === 'is evaluation') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
      }
      break;
    }

    // Return the view with appraisee, evaluator, and appraisal ID data
    return view('pe-pages.pe_self_eval_greyedout', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
  }

  public function deleteKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $kraID = $request->input('kraID');
    $selfEvalkraID = $kraID-1;
    
    // Perform the actual deletion of the KRA record from the database
    try {
      KRA::where('kra_id', $kraID)->delete();
      KRA::where('kra_id', $selfEvalkraID)->delete();

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error deleting KRA record.']);
    }
  }

  public function savePEAppraisal(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $validator = $this->validatePEAppraisal($request);
    if ($validator->fails()) {
      // Display validation errors using dd()
      dd($validator->errors());

      // You can also redirect back with the errors if needed
      // return redirect()->back()->withErrors($validator)->withInput();
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
        'kra_locked' => false,
        'pr_locked' => false,
        'eval_locked' => false,
        'locked' => true,
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

        // Log some information for debugging
        Log::info('Trying to update the record.');
        Log::info('Table Name: ' . (new FinalScores)->getTable());
        Log::info('Employee ID: ' . $employee_id);
        Log::info('Final Score: ' . $finalScore[0]);

        // Attempt to update the record
        try {
          FinalScores::updateOrCreate(
            [
              'employee_id' => $employee_id,
              'department_id' => $departmentId,
            ],
            ['final_score' => $finalScore[0]]
          );

          Log::info('Record updated successfully.');
        } catch (\Exception $e) {
          Log::error('Error while updating the record: ' . $e->getMessage());
        }
      }

      DB::commit();
      return redirect()->route('viewPEAppraisalsOverview')->with('success', 'Submission Complete!');
    } catch (\Exception $e) {
      DB::rollBack();

      // Log the exception
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      // Display exception details using dd()
      dd('An error occurred while saving data.', $e->getMessage(), $e->getTraceAsString());

      return redirect()->back()->with('error', 'An error occurred while saving data.');
    }
  }

  protected function validatePEAppraisal(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    return Validator::make($request->all(), [
      'appraisalID' => 'required|numeric',

      'SIGN.JI.*' => 'required',

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
      // 'feedback.*.*.comments' => 'required|string',
    ], [
      // Custom error messages
    ]);
  }

  public function autosaveKRAField(Request $request)
  {
    // Retrieve the data sent from the frontend
    $kraID = $request->input('kraID');
    $fieldName = $request->input('fieldName');
    $appraisalId = $request->input('appraisalId');

    Log::info('FN' . $fieldName);

    $fieldNameParts = explode('_', $fieldName); // Split into parts
    array_shift($fieldNameParts); // Remove the first part "KRA"
    $newFieldName = implode('_', $fieldNameParts); // Join the remaining parts with underscores

    $fieldValue = $request->input('fieldValue');

    Log::info($newFieldName);
    Log::info($fieldValue);

    try {
      // Find the KRA by ID
      $kra = KRA::find($kraID);
      $typeChecker = Appraisals::where($appraisalId)->pluck('evaluation_type');

      if ($kra) {
        // Update the specific field value
        $kra->$newFieldName = $fieldValue;
        $kra->save();

        if ($typeChecker === 'is evaluation') {
          $kra = KRA::where('appraisal_id', $appraisalId - 1);

          $kra->$newFieldName = $fieldValue;
          $kra->save();
        }
      } else {
        // Create a new KRA record with the provided ID and field value
        $kra = new KRA([
          'kra_id' => $kraID,
          'appraisal_id' => $appraisalId,
          'kra_order' => $kraID,
          $fieldName => $fieldValue
        ]);

        $kra->save();

        if ($typeChecker === 'is evaluation') {
          $kra = new KRA([
            'kra_id' => $kraID,
            'appraisal_id' => $appraisalId - 1,
            'kra_order' => $kraID,
            $fieldName => $fieldValue
          ]);
          $kra->save();
        }
        return response()->json(['message' => 'KRA created and autosave successful']);
      }
      return response()->json(['message' => 'Autosave successful']);
    } catch (\Exception $e) {
      Log::error('Exception Message: ' . $e->getMessage());
      Log::error('Exception Line: ' . $e->getLine());
      Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

      // Handle errors if any
      return response()->json(['error' => 'Autosave failed'], 500);
    }
  }

  // public function autosaveKRAField(Request $request)
  // {
  //   // Retrieve the data sent from the frontend
  //   $kraID = $request->input('kraID');
  //   $fieldName = $request->input('fieldName');
  //   $appraisalId = $request->input('appraisalId');

  //   $fieldNameParts = explode('_', $fieldName); // Split into parts
  //   array_shift($fieldNameParts); // Remove the first part "KRA"
  //   $newFieldName = implode('_', $fieldNameParts); // Join the remaining parts with underscores

  //   $fieldValue = $request->input('fieldValue');

  //   Log::info($newFieldName);
  //   Log::info($fieldValue);

  //   try {
  //     // Find the KRA by ID
  //     $kra = KRA::find($kraID);

  //     if (!$kra) {
  //       // Create a new KRA record with the provided ID and field value
  //       if ($newFieldName === "performance_level") {
  //         $kra = new AppraisalAnswers([
  //           'kra_id' => $kraID,
  //           // Assuming kra_id is set as the ID attribute
  //           'appraisal_id' => $appraisalId,
  //           'score' => $fieldValue
  //         ]);
  //       } else {
  //         $kra = new KRA([
  //           'kra_id' => $kraID,
  //           // Assuming kra_id is set as the ID attribute
  //           'appraisal_id' => $appraisalId,
  //           'kra_order' => $kraID,
  //           $newFieldName => $fieldValue
  //         ]);
  //       }

  //       $kra->save();

  //       return response()->json(['message' => 'KRA created and autosave successful']);
  //     }

  //     // Update the specific field value
  //     if ($newFieldName === "performance_level") {
  //       $kra = new AppraisalAnswers([
  //         'kra_id' => $kraID,
  //         // Assuming kra_id is set as the ID attribute
  //         'appraisal_id' => $appraisalId,
  //         'score' => $fieldValue
  //       ]);
  //     } else {
  //       $kra->setAttribute($newFieldName, $fieldValue);
  //       $kra->save();
  //     }


  //     return response()->json(['message' => 'Autosave successful']);
  //   } catch (\Exception $e) {
  //     Log::error('Exception Message: ' . $e->getMessage());
  //     Log::error('Exception Line: ' . $e->getLine());
  //     Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

  //     // Handle errors if any
  //     return response()->json(['error' => 'Autosave failed'], 500);
  //   }
  // }

  public function autosaveWPPField(Request $request)
  {
    // Retrieve the data sent from the frontend
    $wppID = $request->input('wppID');
    $fieldName = $request->input('fieldName');
    $fieldValue = $request->input('fieldValue');
    $appraisalId = $request->input('appraisalId');

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

      // Log the updated or inserted record
      $wpaData = WPP::where(['appraisal_id' => $appraisalId, 'performance_plan_id' => $wpp->performance_plan_id])->get();

      // Return the ID in the response
      return response()->json(['message' => 'Autosave successful', 'wpaData' => $wpaData]);
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
          'appraisal_id' => $appraisalId,
          'development_plan_order' => $ldpID,
          $fieldName => $fieldValue
        ]);
        $ldp->save();
      }

      // Log the updated or inserted record
      $ldpData = LDP::where(['appraisal_id' => $appraisalId, 'development_plan_id' => $ldp->development_plan_id])->get();

      // Return the ID in the response
      return response()->json(['message' => 'Autosave successful', 'ldpData' => $ldpData]);
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

  protected function createKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    foreach ($request->input('KRA') as $kraID => $kraData) {
      $existingKRA = KRA::where('appraisal_id', $request->input('appraisalID'))
        ->where('kra_id', $kraID)
        ->first();

      $kraWeight = $kraData[$request->input('appraisalID')]['KRA_kra_weight'] / 100; // Convert percentage to decimal
      $performanceLevel = $kraData[$request->input('appraisalID')]['KRA_performance_level'];
      Log::info('WEIGHT: ' . $kraWeight);
      Log::info('performanceLevel: ' . $performanceLevel);

      // Calculate the weighted total
      $weightedTotal = $kraWeight * $performanceLevel;
      Log::info('weightedTotal: ' . $weightedTotal);

      if ($existingKRA) {
        if (
          $existingKRA->kra !== $kraData[$request->input('appraisalID')]['KRA_kra'] ||
          $existingKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_kra_weight'] ||
          $existingKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
          $existingKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator'] ||
          $existingKRA->performance_level !== $kraData[$request->input('appraisalID')]['KRA_performance_level'] ||
          $existingKRA->weighted_total !== $weightedTotal
        ) {
          $existingKRA->update([
            'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
            'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
            'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
            'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
            'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
            'weighted_total' => $weightedTotal,
          ]);
        }
      } else {
        KRA::create([
          'appraisal_id' => $request->input('appraisalID'),
          'kra' => $kraData[$request->input('appraisalID')]['KRA'],
          'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
          'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
          'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
          'kra_order' => $kraID,
        ]);
      }
      ///////////////////////////////////////////SELF EVAL//////////////////////////////////////////////////////////////
      // $existingSelfEvalKRA = KRA::where('appraisal_id', $request->input('appraisalID') - 1)
      //   ->where('kra_id', $kraID + 1)
      //   ->first();

      // if ($existingSelfEvalKRA) {
      //   Log::info($existingSelfEvalKRA);
      //   Log::info('KRA ID: ' . ($kraID - 1));
      //   Log::info('Appraisal ID ' . ($request->input('appraisalID') - 1));

      //   if (
      //     $existingSelfEvalKRA->kra !== $kraData[$request->input('appraisalID')]['KRA_kra'] ||
      //     $existingSelfEvalKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_kra_weight'] ||
      //     $existingSelfEvalKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
      //     $existingSelfEvalKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator'] ||
      //     $existingSelfEvalKRA->performance_level !== $kraData[$request->input('appraisalID')]['KRA_performance_level'] ||
      //     $existingKRA->weighted_total !== $weightedTotal
      //   ) {
      //     $existingSelfEvalKRA->update([
      //       'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
      //       'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
      //       'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
      //       'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
      //       'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
      //       'weighted_total' => $weightedTotal,
      //     ]);
      //   }
      // } else {
      //   Log::info('No matching KRA found.');
      //   Log::info('KRA ID: ' . ($kraID - 1));
      //   Log::info('Appraisal ID ' . ($request->input('appraisalID') - 1));

      //   KRA::create([
      //     'appraisal_id' => $request->input('appraisalID') - 1,
      //     'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
      //     'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
      //     'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
      //     'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
      //     'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
      //     'kra_order' => $kraID,
      //   ]);
      // }
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
        ->where('sign_type', 'SE')
        ->first();

      if ($existingSignature) {
        // Update the existing signature data
        $existingSignature->update([
          'sign_data' => $signatureData,
          'sign_type' => 'SE',
        ]);
      } else {
        // Create a new signature if it doesn't exist
        try {
          // Create a new signature and retrieve its ID
          $newSignature = Signature::create([
            'appraisal_id' => $appraisalId,
            'sign_data' => $signatureData,
            'sign_type' => 'SE',
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

  public function formChecker(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    // $kraLocked = 1;
    // $prLocked = 1;
    // $evalLocked = 1;
    // $fullLocked = 1;

    $account_id = session()->get('account_id');
    $appraisalId = $request->input('appraisalId');
    $appraisal = Appraisals::find($appraisalId);
    $submitionChecker = $appraisal->date_submitted;

    $userEmployeeId = Employees::where('account_id', $account_id)->pluck('employee_id')->first();

    if ($appraisal) {
      ////////////PERMISSION/////////////
      $employeeId = $appraisal->employee_id;
      $evaluatorId = $appraisal->evaluator_id;

      $isAdmin = Accounts::where('account_id', $account_id)->where('type', 'AD')->exists();

      $isImmediateSuperior = Accounts::where('account_id', $account_id)
            ->where('type', 'IS')
            ->whereHas('employee', function ($query) use ($appraisal) {
                $query->where('department_id', $appraisal->department_id);
            })
            ->exists();

      $isEvaluator = ($userEmployeeId == $evaluatorId);
      $isEmployee = ($userEmployeeId == $employeeId);

      // Check permissions for viewing the form
      if (!($isAdmin || $isEvaluator || $isEmployee || $isImmediateSuperior)) {
        return response()->json(['success' => false, 'message' => 'You do not have permission to view this form.'], 403);
      }

      ////////////LOCK/////////////
      // Lock statuses stored as binary with text keys
      $locks = [];

      $locks['kra'] = $appraisal->kra_locked == 1;
      $locks['pr'] = $appraisal->pr_locked == 1;
      $locks['eval'] = $appraisal->eval_locked == 1;
      $locks['lock'] = $appraisal->locked !== 1;

      ////////////PHASES/////////////
      $activeYear = EvalYear::where('status', 'active')->first();

      $currentDate = Carbon::now();
      $currentDate = now();

      // $locks['dateNow'] = $currentDate;

      // $currentDate = Carbon::parse("2023-10-31"); //KRA
      // $currentDate = Carbon::parse("2023-11-11"); //PR
      // $currentDate = Carbon::parse("2023-11-22"); //EVAL

      $kraStart = Carbon::parse($activeYear->kra_start);
      $kraEnd = Carbon::parse($activeYear->kra_end);
      $prStart = Carbon::parse($activeYear->pr_start);
      $prEnd = Carbon::parse($activeYear->pr_end);
      $evalStart = Carbon::parse($activeYear->eval_start);
      $evalEnd = Carbon::parse($activeYear->eval_end);

      if ($currentDate->between($kraStart, $kraEnd)) {
        $phaseData = "kra";
      } elseif ($currentDate->between($prStart, $prEnd)) {
        $phaseData = "pr";
      } elseif ($currentDate->between($evalStart, $evalEnd)) {
        $phaseData = "eval";
      } else {
        $phaseData = "lock";
        $locks['lock'] = false;
      }

      $isSubmissionMade = !is_null($submitionChecker) && $appraisal->locked == 1;

      // $hasRequest = Requests::where('appraisal_id', $appraisalId)->where('status', 'Pending')->exists();
      $hasRequest = Requests::where('appraisal_id', $appraisalId)
      ->whereIn('status', ['Pending', 'Approved', 'Disapproved'])
      ->latest('created_at') // Get the latest entry
      ->first();

      $response = [
        'success' => true,
        'locks' => $locks,
        'phaseData' => $phaseData,
        'submitionChecker' => $isSubmissionMade,
        'hasRequest' => $hasRequest !== null,
      ];

      if ($hasRequest) {
          if($appraisal->eval_locked == 1){
            $locks['lock'] = true;
          }

          // Get additional information
          $approver = Employees::find($hasRequest->approver_id);

          $timestamp = $hasRequest->updated_at;
          $updated_at = Carbon::parse($timestamp)->format('F j, Y H:i:s');

          // Add information to the response
          $response['status'] = $hasRequest->status;
          $response['feedback'] = $hasRequest->feedback;
          $response['approver_name'] = $approver ? $approver->first_name . ' ' . $approver->last_name : null;
          $response['approved_at'] = $updated_at;
      }

      return response()->json($response);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
    }
  }

  function calculateFinalScore($appraisals)
  {
    $behavioralCompetenciesWeightedTotal = 0;
    $kraGrade = 0;
    $kraFinalScore = 0;
    $allSubmitted = 0;
    $kraFormsCount = 0;
    $finalGrade = null;

    $weightedTotals = [
      'self evaluation' => 0,
      'is evaluation' => 0,
      'internal customer 1' => 0,
      'internal customer 2' => 0,
    ];

    Log::info('Starting final score calculation');
    Log::info('Appraisal List:');
    Log::info($appraisals);

    foreach ($appraisals as $appraisal) {
      $evaluationType = $appraisal['evaluation_type'];
      $bhScore = $appraisal['bh_score'];
      $kraScore = $appraisal['kra_score'];
      $icScore = $appraisal['ic_score'];

      Log::info('Processing appraisal for evaluation type: ' . $evaluationType);
      Log::info('Processing appraisal: ' . $appraisal);
      Log::info('BH Score: ' . $bhScore);
      Log::info('KRA Score: ' . $kraScore);
      Log::info('IC Score: ' . $icScore);
      // Log::info('allSubmitted Before Processing:' . $allSubmitted);
      Log::info('allSubmitted kraGrade: ' . $kraGrade);
      Log::info('allSubmitted kraScore: ' . $kraScore);
      Log::info('allSubmitted kraFormsCount: ' . $kraFormsCount);

      if ($appraisal['date_submitted'] !== null) {
        // Retrieve the latest active evaluation year
        $latestActiveEvalYear = EvalYear::where('status', 'active')->latest('eval_id')->first();

        if (!$latestActiveEvalYear) {
          Log::error('Latest active evaluation year not found. Handle this case as needed.');
        } else {
          $evalYearId = $latestActiveEvalYear->eval_id;
          Log::info('Latest active evaluation id founded:' . $evalYearId);

          // Retrieve the score weights for the current evaluation type in the latest active year
          $scoreWeights = ScoreWeights::where('eval_id', $evalYearId)->first();

          if ($scoreWeights) {
            Log::info('Latest active scoreWeights id founded:' . $scoreWeights);

            $selfEvalWeight = $scoreWeights->self_eval_weight / 100;
            $ic1Weight = $scoreWeights->ic1_weight / 100;
            $ic2Weight = $scoreWeights->ic2_weight / 100;
            $isWeight = $scoreWeights->is_weight / 100;

            if ($evaluationType === 'self evaluation' || $evaluationType === 'is evaluation') {
              $kraGrade += $kraScore;
              $kraFormsCount++;
              Log::info('allSubmitted kraGrade: ' . $kraGrade);
              Log::info('allSubmitted kraScore: ' . $kraScore);
              Log::info('allSubmitted kraFormsCount: ' . $kraFormsCount);

              // Update the weighted total based on the evaluation type
              if ($evaluationType === 'self evaluation') {
                $weightedTotals['self evaluation'] += ($bhScore * $selfEvalWeight);
                $behavioralCompetenciesWeightedTotal += $weightedTotals['self evaluation'];

              } elseif ($evaluationType === 'is evaluation') {
                $weightedTotals['is evaluation'] += ($bhScore * $isWeight);
                $behavioralCompetenciesWeightedTotal += $weightedTotals['is evaluation'];

              }
            } elseif ($evaluationType === 'internal customer 1') {
              $weightedTotals['internal customer 1'] += ($icScore * $ic1Weight);
              $behavioralCompetenciesWeightedTotal += $weightedTotals['internal customer 1'];

            } elseif ($evaluationType === 'internal customer 2') {
              $weightedTotals['internal customer 2'] += ($icScore * $ic2Weight);
              $behavioralCompetenciesWeightedTotal += $weightedTotals['internal customer 2'];
            }

          } else {
            Log::error('Final Grade calculation skipped due to scoreWeights being null for an appraisal.');
          }
        }
        $allSubmitted = 1;
      } else {
        Log::error('Final Grade calculation skipped due to date_submitted being null for an appraisal.');

        $allSubmitted = 0;
        $finalGrade = null;
        $kraFormsCount = null;
        break;
      }
    }

    Log::info('allSubmitted After Processing:' . $allSubmitted);

    if ($allSubmitted) {
      Log::info('allSubmitted kraGrade: ' . $kraGrade);
      Log::info('allSubmitted kraScore: ' . $kraScore);
      Log::info('allSubmitted kraFormsCount: ' . $kraFormsCount);

      $kraFinalScore = $kraGrade / $kraFormsCount;
      $scoreWeights = ScoreWeights::where('eval_id', $evalYearId)->first();

      $bhWeight = $scoreWeights->bh_weight / 100;
      $kraWeight = $scoreWeights->kra_weight / 100;

      $finalGrade = ($behavioralCompetenciesWeightedTotal * $bhWeight) + ($kraFinalScore * $kraWeight);

      Log::info('Final Grade Calculation:');
      Log::info('Self Eval Weighted Total: ' . $weightedTotals['self evaluation']);
      Log::info('Immediate Superior Weighted Total: ' . $weightedTotals['is evaluation']);
      Log::info('Internal Cust 1 Weighted Total: ' . $weightedTotals['internal customer 1']);
      Log::info('Internal Cust 2 Weighted Total: ' . $weightedTotals['internal customer 2']);

      Log::info('Behavioral Competencies Weighted Total: ' . $behavioralCompetenciesWeightedTotal);
      Log::info('KRA Weighted Total: ' . $kraFinalScore);
      Log::info('Final Grade Computation: (' . $behavioralCompetenciesWeightedTotal . ' x ' . $scoreWeights->bh_weight . '%)  + (' . $kraFinalScore . ' x ' . $scoreWeights->kra_weight . '%)');
      Log::info('Final Grade: ' . $finalGrade);
    } else {
      Log::info('allSubmitted Error Log:' . $allSubmitted);

      Log::error('Final Grade calculation skipped due to date_submitted being null for an appraisal.');
    }

    Log::info('Final Score Calculation Complete');

    return [$finalGrade, $behavioralCompetenciesWeightedTotal, $kraFinalScore, $weightedTotals];
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

  public function saveEULA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    // Get the appraisal ID from the request
    $appraisalId = $request->input('appraisalId');

    // Update the 'eula' column to true for the specified appraisal ID
    try {
      Appraisals::where('appraisal_id', $appraisalId)->update(['eula' => 1]);
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error updating EULA status.']);
    }
  }

  public function submitRequest(Request $request)
  {
      // Check if the user is authenticated
      if (!session()->has('account_id')) {
          return view('auth.login');
      }

      // Validate the form data
      $request->validate([
          'request' => 'required|string',
      ]);

      try {
          // Create a new RequestModel instance and populate it with form data
          $newRequest = new Requests;
          $newRequest->appraisal_id = $request->input('appraisal_id');
          $newRequest->request = $request->input('request');
          $newRequest->status = 'Pending'; // Set an initial status

          $newRequest->save();

          return response()->json(['success' => true]);
      } catch (\Exception $e) {
          Log::error('Exception Message: ' . $e->getMessage());
          Log::error('Exception Line: ' . $e->getLine());
          Log::error('Exception Stack Trace: ' . $e->getTraceAsString());
          return response()->json(['success' => false, 'message' => 'Error submitting the request.']);
      }
  }

  public function calculateAndStoreFinalScoresForAllEmployees()
    {
        // Get all distinct employee IDs from the Appraisals table
        $employeeIds = Appraisals::distinct('employee_id')->pluck('employee_id');

        foreach ($employeeIds as $employee_id) {
            // Get all appraisals for the current employee
            $appraisalData = Appraisals::where('employee_id', $employee_id)->get();

            // Check if all appraisals have a non-null date_submitted
            $allSubmitted = $appraisalData->every(function ($appraisal) {
                return $appraisal->date_submitted !== null;
            });

            if ($allSubmitted) {
                // Get the department ID directly from the Employee model
                $departmentId = Employees::where('employee_id', $employee_id)->value('department_id');

                // Calculate the final score
                $finalScore = $this->calculateFinalScore($appraisalData);

                // Log some information for debugging
                Log::info('Trying to update the record.');
                Log::info('Table Name: ' . (new FinalScores)->getTable());
                Log::info('Employee ID: ' . $employee_id);
                Log::info('Final Score: ' . $finalScore[0]);

                // Attempt to update the record
                FinalScores::updateOrCreate(
                    [
                        'employee_id' => $employee_id,
                        'department_id' => $departmentId,
                    ],
                    ['final_score' => $finalScore[0]]
                );

                Log::info('Record updated successfully for Employee ID: ' . $employee_id);
            } else {
                Log::info('Not all appraisals have been submitted for Employee ID: ' . $employee_id);
            }
        }

        // You can return a response or redirect as needed
        return response()->json(['message' => 'Final scores updated successfully for all employees']);
    }
}