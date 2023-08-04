<?php

namespace App\Http\Controllers\PermanentEmployee;


use App\Models\KRA;
use App\Http\Controllers\Controller;
use App\Models\FormQuestions;

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
        $SID = FormQuestions::where('table_initials', 'SID')->get();
        $SR = FormQuestions::where('table_initials', 'SR')->get();
        $S = FormQuestions::where('table_initials', 'S')->get();

        $data = [
            'success' => true,
            'SID' => $SID,
            'SR' => $SR,
            'S' => $S
        ];

        return response()->json($data);
    }
    
    public function showAppraisalForm()
    {
        // Retrieve the KRA data from the database
        $kraData = KRA::where('appraisal_id', '1')->get(); // Replace '1' with the appropriate appraisal_id that you want to display

        // Return the KRA data as a JSON response
        return response()->json(['success' => true, 'isAppraisalData' => $kraData]);
    }
}