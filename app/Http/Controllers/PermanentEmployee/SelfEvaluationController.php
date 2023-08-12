<?php

namespace App\Http\Controllers\PermanentEmployee;

use App\Models\KRA;
use App\Http\Controllers\Controller;
use App\Models\FormQuestions;
use App\Models\AppraisalAnswers;
use App\Models\Employees;
use App\Models\Appraisals;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}