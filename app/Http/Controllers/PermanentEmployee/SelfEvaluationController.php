<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\EvalYear;
use App\Models\FinalScores;
use App\Models\KRA;
use App\Models\WPP;
use App\Models\LDP;
use App\Models\JIC;
use App\Models\Signature;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\FormQuestions;
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

    $SID = FormQuestions::where('table_initials', 'SID')->where('status', 'active')->get();
    $SR = FormQuestions::where('table_initials', 'SR')->where('status', 'active')->get();
    $S = FormQuestions::where('table_initials', 'S')->where('status', 'active')->get();

    // Retrieve stored scores for each question ID
    $storedValues = AppraisalAnswers::where('appraisal_id', $appraisalId)
      ->pluck('score', 'question_id') // Retrieves scores with question IDs as keys
      ->toArray();

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
      ->with('employee', 'evaluator') // Load the related employee and evaluator information
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

  public function getPEKRA(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisal_id');
    $eulaData = Appraisals::where('appraisal_id', $appraisalId)->pluck('eula');
    $kraData = KRA::where('appraisal_id', $appraisalId)->get();
    $wpaData = WPP::where('appraisal_id', $appraisalId)->get();
    $ldpData = LDP::where('appraisal_id', $appraisalId)->get();
    $jicData = JIC::where('appraisal_id', $appraisalId)->get();
    $signData = Signature::where('appraisal_id', $appraisalId)
    ->with('appraisal.employee:employee_id,first_name,last_name')
    ->get();
    return response()->json(['success' => true, 'eulaData' => $eulaData, 'kraData' => $kraData, 'wpaData' => $wpaData, 'ldpData' => $ldpData, 'jicData' => $jicData, 'signData' => $signData]);
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

      'SIGN.JI.*' => 'required|image|mimes:jpeg,png,jpg|max:50000',

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
          $kra = KRA::where('appraisal_id',$appraisalId - 1);

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
      $existingSelfEvalKRA = KRA::where('appraisal_id', $request->input('appraisalID') - 1)
        ->where('kra_id', $kraID + 1)
        ->first();

      if ($existingSelfEvalKRA) {
        Log::info($existingSelfEvalKRA);
        Log::info('KRA ID: ' . ($kraID - 1));
        Log::info('Appraisal ID ' . ($request->input('appraisalID') - 1));

        if (
          $existingSelfEvalKRA->kra !== $kraData[$request->input('appraisalID')]['KRA_kra'] ||
          $existingSelfEvalKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_kra_weight'] ||
          $existingSelfEvalKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
          $existingSelfEvalKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator'] ||
          $existingSelfEvalKRA->performance_level !== $kraData[$request->input('appraisalID')]['KRA_performance_level'] ||
          $existingKRA->weighted_total !== $weightedTotal
        ) {
          $existingSelfEvalKRA->update([
            'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
            'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
            'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
            'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
            'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
            'weighted_total' => $weightedTotal,
          ]);
        }
      } else {
        Log::info('No matching KRA found.');
        Log::info('KRA ID: ' . ($kraID - 1));
        Log::info('Appraisal ID ' . ($request->input('appraisalID') - 1));

        KRA::create([
          'appraisal_id' => $request->input('appraisalID') - 1,
          'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
          'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_kra_weight'],
          'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
          'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
          'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
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

    $appraisalId = $request->input('appraisalID'); // Add a semicolon here
    $signatureFile = $request->file('SIGN.JI.' . $appraisalId);

    // Check if the file exists and is valid
    if ($signatureFile && $signatureFile->isValid()) {
      $existingSignature = Signature::where('appraisal_id', $appraisalId)
        ->where('sign_type', 'JI')
        ->first();

      if ($existingSignature) {
        // Get signature data from uploaded file
        $signatureData = file_get_contents($signatureFile->getRealPath());

        $existingSignature->update([
          'sign_data' => $signatureData,
          'sign_type' => 'JI',
        ]);
      } else {
        try {
          $signatureData = file_get_contents($signatureFile->getRealPath());

          // Create a new signature and retrieve its ID
          $newSignature = Signature::create([
            'appraisal_id' => $appraisalId,
            'sign_data' => $signatureData,
            'sign_type' => 'JI',
            // You should add other necessary fields here
          ]);

          // Get the ID of the newly created signature
          $newSignatureId = $newSignature->signature_id;
        } catch (QueryException $e) {
          // Handle the database connection issue
          // You can log the error or display a user-friendly message
          dd('Error: ' . $e->getMessage());
        }
      }
    }
  }

  public function formChecker(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $appraisalId = $request->input('appraisalId');
    $appraisal = Appraisals::find($appraisalId);

    if ($appraisal) {
      ////////////LOCK/////////////
      $kraLocked = $appraisal->kra_locked;
      $prLocked = $appraisal->pr_locked;
      $evalLocked = $appraisal->eval_locked;
      $fullLocked = $appraisal->locked;

      // $kraLocked = 1;
      // $prLocked = $appraisal->pr_locked;
      // $evalLocked = $appraisal->eval_locked;
      // $fullLocked = $appraisal->locked;
      // Log::info($kraLocked);
      // Log::info($prLocked);
      // Log::info($evalLocked);
      // Log::info($fullLocked);

      $locked = null;

      if ($kraLocked == true) {
        $locked = "kra";
      } elseif ($prLocked == true) {
        $locked = "pr";
      } elseif ($evalLocked == true) {
        $locked = "eval";
      } elseif ($fullLocked == true) {
        $locked = "lock";
      }
      Log::info($locked);

      ////////////PHASES/////////////
      // $currentDate = Carbon::now();

      // $currentDate = Carbon::parse("2023-10-31"); //KRA
      // $currentDate = Carbon::parse("2023-11-11"); //PR
      $currentDate = Carbon::parse("2023-11-22"); //EVAL

      $activeYear = EvalYear::where('status', 'active')->first();

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
      }

      return response()->json(['success' => true, 'locked' => $locked, 'phaseData' => $phaseData]);
    } else {
      return response()->json(['success' => false, 'message' => 'Appraisal not found'], 404);
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
}