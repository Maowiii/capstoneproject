<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
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

    $SID = FormQuestions::where('table_initials', 'SID')->get();
    $SR = FormQuestions::where('table_initials', 'SR')->get();
    $S = FormQuestions::where('table_initials', 'S')->get();

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

    $appraisee = Employees::where('account_id', $account_id)
      ->get();

    $appraisals = Appraisals::where('employee_id', $user->employee_id)
      ->with('employee', 'evaluator') // Load the related employee and evaluator information
      ->get();

    $data = [
      'success' => true,
      'appraisee' => $appraisee,
      'appraisals' => $appraisals,
      'is' => $user
    ];

    return response()->json($data);
  }

  public function viewAppraisal($appraisal_id)
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
        return view('pe-pages.pe_self_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
      } elseif ($appraisalType === 'internal customer 1' || $appraisalType === 'internal customer 2') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
        return view('pe-pages.pe_ic_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
      } elseif ($appraisalType === 'is evaluation') {
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisal_Id = $appraisal->appraisal_id;
        return view('pe-pages.pe_self_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
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
      } elseif (str_starts_with($appraisalType, 'internal customer')) {
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
    $kraData = KRA::where('appraisal_id', $appraisalId)->get();
    $wpaData = WPP::where('appraisal_id', $appraisalId)->get();
    $ldpData = LDP::where('appraisal_id', $appraisalId)->get();
    $jicData = JIC::where('appraisal_id', $appraisalId)->get();
    $signData = Signature::where('appraisal_id', $appraisalId)->get();

    foreach ($signData as &$sign) {
      $sign->sign_data = base64_encode($sign->sign_data);
    }

    return response()->json(['success' => true, 'kraData' => $kraData, 'wpaData' => $wpaData, 'ldpData' => $ldpData, 'jicData' => $jicData, 'signData' => $signData]);
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

            $existingRecord->update([
                'date_submitted' => $date,
            ]);

            DB::commit();
            return redirect()->route('viewPEAppraisalsOverview')->with('success', 'Submission Complete!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the exception
            Log::error('Exception Message: ' . $e->getMessage());
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
          'KRA.*.*.KRA_weight' => 'required|numeric',
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

    // public function autosaveKRAField(Request $request)
    // {
    //     // Retrieve the data sent from the frontend
    //     $kraID = $request->input('kraID');
    //     $fieldName = $request->input('fieldName');
    //     $appraisalId = $request->input('appraisalId');
        
    //     Log::info('FN'.$fieldName);

    //     $fieldNameParts = explode('_', $fieldName); // Split into parts
    //     array_shift($fieldNameParts); // Remove the first part "KRA"
    //     $newFieldName = implode('_', $fieldNameParts); // Join the remaining parts with underscores

    //     $fieldValue = $request->input('fieldValue');

    //     Log::info($newFieldName);
    //     Log::info($fieldValue);

    //     try {
    //         // Find the KRA by ID
    //         $kra = KRA::find($kraID);

    //         if (!$kra) {
    //           // Create a new KRA record with the provided ID and field value
    //           $kra = new KRA([
    //               'kra_id' => $kraID, // Assuming kra_id is set as the ID attribute
    //               'appraisal_id' => $appraisalId,
    //               'kra_order' => $kraID,
    //               $fieldName => $fieldValue
    //           ]);
  
    //           $kra->save();
  
    //           return response()->json(['message' => 'KRA created and autosave successful']);
    //       }
  
    //       // Update the specific field value
    //       $kra->$newFieldName = $fieldValue;
    //       $kra->save();
  
    //       return response()->json(['message' => 'Autosave successful']);
    //     } catch (\Exception $e) {
    //         Log::error('Exception Message: ' . $e->getMessage());
    //         Log::error('Exception Line: ' . $e->getLine());
    //         Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

    //         // Handle errors if any
    //         return response()->json(['error' => 'Autosave failed'], 500);
    //     }
    // }

    public function autosaveKRAField(Request $request)
    {
        // Retrieve the data sent from the frontend
        $kraID = $request->input('kraID');
        $fieldName = $request->input('fieldName');
        $appraisalId = $request->input('appraisalId');

        $fieldNameParts = explode('_', $fieldName); // Split into parts
        array_shift($fieldNameParts); // Remove the first part "KRA"
        $newFieldName = implode('_', $fieldNameParts); // Join the remaining parts with underscores

        $fieldValue = $request->input('fieldValue');

        Log::info($newFieldName);
        Log::info($fieldValue);

        try {
            // Find the KRA by ID
            $kra = KRA::find($kraID);

            if (!$kra) {
                // Create a new KRA record with the provided ID and field value
                if($newFieldName === "performance_level"){
                      $kra = new AppraisalAnswers([
                        'kra_id' => $kraID, // Assuming kra_id is set as the ID attribute
                        'appraisal_id' => $appraisalId,
                        'score' => $fieldValue
                    ]);
                }else{
                  $kra = new KRA([
                    'kra_id' => $kraID, // Assuming kra_id is set as the ID attribute
                    'appraisal_id' => $appraisalId,
                    'kra_order' => $kraID,
                    $newFieldName => $fieldValue
                ]);
                }

                $kra->save();

                return response()->json(['message' => 'KRA created and autosave successful']);
            }

            // Update the specific field value
            if ($newFieldName === "performance_level") {
              $kra = new AppraisalAnswers([
                'kra_id' => $kraID, // Assuming kra_id is set as the ID attribute
                'appraisal_id' => $appraisalId,
                'score' => $fieldValue
            ]);
            }else{
              $kra->setAttribute($newFieldName, $fieldValue);
              $kra->save();
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

            if ($existingKRA) {
                if (
                    $existingKRA->kra !== $kraData[$request->input('appraisalID')]['KRA_kra'] ||
                    $existingKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_weight'] ||
                    $existingKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
                    $existingKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator'] ||
                    $existingKRA->actual_result !== $kraData[$request->input('appraisalID')]['KRA_actual_result'] ||
                    $existingKRA->performance_level !== $kraData[$request->input('appraisalID')]['KRA_performance_level'] ||
                    $existingKRA->weighted_total !== $kraData[$request->input('appraisalID')]['KRA_weighted_total']
                ) {
                    $existingKRA->update([
                        'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
                        'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
                        'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
                        'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
                        'actual_result' => $kraData[$request->input('appraisalID')]['KRA_actual_result'],
                        'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
                        'weighted_total' => $kraData[$request->input('appraisalID')]['KRA_weighted_total']
                    ]);

                    
                }
            } else {
                KRA::create([
                    'kra_id' => $kraData[$request->input('appraisalID')]['kraID'],
                    'appraisal_id' => $request->input('appraisalID'),
                    'kra_order' => $kraID,
                    'kra' => $kraData[$request->input('appraisalID')]['KRA_kra'],
                    'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
                    'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
                    'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
                    'actual_result' => $kraData[$request->input('appraisalID')]['KRA_actual_result'],
                    'weighted_total' => $kraData[$request->input('appraisalID')]['KRA_weighted_total']
                ]);

                AppraisalAnswers::create([
                  'kra_id' => $kraData[$request->input('appraisalID')]['kraID'],
                  'appraisal_id' => $request->input('appraisalID'),
                    'score' => $kraData[$request->input('appraisalID')]['KRA_performance_level']
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
        $existingRecord = Appraisals::where('appraisal_id', $appraisalId)->first();

        if ($existingRecord) {
            $existingRecord->update([
                'signature' => $existingSignature ? $existingSignature->signature_id : $newSignatureId,
            ]);
        }
    }

    public function formChecker(Request $request)
    {
        if (!session()->has('account_id')) {
        return view('auth.login');
        }
        
        $appraisalId = $request->input('appraisalId');
        $appraisal = Appraisals::find($appraisalId);
        
        $locked = $appraisal->locked;

        return response()->json([
        'form_submitted' => $locked,
        ]);
    }
}