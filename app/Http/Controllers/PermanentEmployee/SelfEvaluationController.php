<?php

namespace App\Http\Controllers\PermanentEmployee;


use App\Models\KRA;
use App\Http\Controllers\Controller;
use App\Models\AppraisalAnswers;
use App\Models\WPP;
use App\Models\LDP;
use App\Models\JIC;
use App\Models\Appraisals;
use App\Models\Employees;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SelfEvaluationController extends Controller
{
    public function displaySelfEvaluationForm()
    {
        return view('pe-pages.pe_self_evaluation');
    }

    public function getQuestions(Request $request)
    {
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
        $appraisalId = $request->input('appraisal_id');

        $kraData = KRA::where('appraisal_id', $appraisalId)->get();

        // Return the KRA data as a JSON response
        return response()->json(['success' => true, 'isAppraisalData' => $kraData]);
    }

    public function getData(Request $request)
    {
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
        return view('pe-pages.pe_self_evaluation', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisal_Id]);
    }
    public function getPEKRA(Request $request)
    {
        $appraisalId = $request->input('appraisal_id');
        $kraData = KRA::where('appraisal_id', $appraisalId)->get();
        $wpaData = WPP::where('appraisal_id', $appraisalId)->get();
        $ldpData = LDP::where('appraisal_id', $appraisalId)->get();
        $jicData = JIC::where('appraisal_id', $appraisalId)->get();

        return response()->json(['success' => true, 'kraData' => $kraData, 'wpaData' => $wpaData, 'ldpData' => $ldpData,'jicData' => $jicData]);
    }
    public function deleteKRA(Request $request)
    {
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
        $validator = $this->validatePEAppraisal($request);
        if ($validator->fails()) {
            // Log the validation errors
            Log::error('Validation Errors: ' . json_encode($validator->errors()));

            // Display validation errors using dd()
            dd($validator->errors());

            // You can also redirect back with the errors if needed
            // return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            /*            
            $this->createSID($request);
            $this->createSR($request);
            $this->createS($request);
            $this->createKRA($request);
            $this->createWPA($request);
            $this->createLDP($request);
            */
            $this->createJIC($request);

            DB::commit();
            return redirect()->route('viewPEAppraisalsOverview')->with('success', 'Submition Complete!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the exception
            Log::error('Exception Message: ' . $e->getMessage());
            Log::error('Exception Stack Trace: ' . $e->getTraceAsString());

            // Display exception details using dd()
            dd('An error occurred while saving data.', $e->getMessage(), $e->getTraceAsString());

            // return redirect()->back()->with('error', 'An error occurred while saving data.');
        }

    }

    protected function validatePEAppraisal(Request $request)
    {
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

            'KRA' => 'required|array',
            'KRA.*' => 'required|array',
            'KRA.*.*.kraID' => 'required|numeric',
            'KRA.*.*.KRA' => 'required|string',
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
            */
            'feedback' => 'required|array',
            'feedback.*' => 'required|array',
            'feedback.*.*.question' => 'required|string',
            'feedback.*.*.answer' => 'required|numeric',
            'feedback.*.*.comment' => 'required|string',

        ], [
            // Custom error messages
        ]);
    }

    protected function createSID(Request $request)
    {
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
        foreach ($request->input('KRA') as $kraID => $kraData) {
            $existingKRA = KRA::where('appraisal_id', $request->input('appraisalID'))
                ->where('kra_id', $kraID)
                ->first();

            if ($existingKRA) {
                if (
                    $existingKRA->kra !== $kraData[$request->input('appraisalID')]['KRA'] ||
                    $existingKRA->kra_weight !== $kraData[$request->input('appraisalID')]['KRA_weight'] ||
                    $existingKRA->objective !== $kraData[$request->input('appraisalID')]['KRA_objective'] ||
                    $existingKRA->performance_indicator !== $kraData[$request->input('appraisalID')]['KRA_performance_indicator'] ||
                    $existingKRA->actual_result !== $kraData[$request->input('appraisalID')]['KRA_actual_result'] ||
                    $existingKRA->performance_level !== $kraData[$request->input('appraisalID')]['KRA_performance_level'] ||
                    $existingKRA->weighted_total!== $kraData[$request->input('appraisalID')]['KRA_weighted_total']
                ) {
                    $existingKRA->update([
                        'kra' => $kraData[$request->input('appraisalID')]['KRA'],
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
                    'kra' => $kraData[$request->input('appraisalID')]['KRA'],
                    'kra_weight' => $kraData[$request->input('appraisalID')]['KRA_weight'],
                    'objective' => $kraData[$request->input('appraisalID')]['KRA_objective'],
                    'performance_indicator' => $kraData[$request->input('appraisalID')]['KRA_performance_indicator'],
                    'actual_result' => $kraData[$request->input('appraisalID')]['KRA_actual_result'],
                    'performance_level' => $kraData[$request->input('appraisalID')]['KRA_performance_level'],
                    'weighted_total' => $kraData[$request->input('appraisalID')]['KRA_weighted_total']
                ]);
            }
        }

    }

    protected function createWPA(Request $request)
    {
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
        foreach ($request->input('feedback') as $jicID => $jicData) {
            $existingJIC = JIC::where('appraisal_id', $request->input('appraisalID'))
                ->where('job_incumbent_id', $jicID)
                ->first();

            if ($existingJIC) {
                if (
                    $existingJIC->job_incumbent_question !== $jicData[$request->input('appraisalID')]['question'] ||
                    $existingJIC->answer !== $jicData[$request->input('appraisalID')]['answer'] ||
                    $existingJIC->comments !== $jicData[$request->input('appraisalID')]['comments']
                ) {
                    $existingJIC->update([
                        'job_incumbent_question' => $jicData[$request->input('appraisalID')]['question'],
                        'answer' => $jicData[$request->input('appraisalID')]['answer'],
                        'comments' => $jicData[$request->input('appraisalID')]['comment'],
                    ]);
                }
            } else {
                JIC::create([
                    'appraisal_id' => $request->input('appraisalID'),
                    'job_incumbent_question' => $jicData[$request->input('appraisalID')]['question'],
                    'answer' => $jicData[$request->input('appraisalID')]['answer'],
                    'comments' => $jicData[$request->input('appraisalID')]['comment'],
                ]);
            }
        }
    }
}
