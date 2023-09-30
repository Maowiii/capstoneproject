<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EditableFormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class EditableAppraisalFormController extends Controller
{
  public function displayEditableAppraisalForm()
  {
    if (session()->has('account_id')) {
      return view('admin-pages.editable_appraisal_form');
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }
  }
  public function getAppraisalQuestions()
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    $SID = EditableFormQuestions::where('table_initials', 'SID')->where('status', 'active')
      ->get();
    $SR = EditableFormQuestions::where('table_initials', 'SR')->where('status', 'active')
      ->get();
    $S = EditableFormQuestions::where('table_initials', 'S')->where('status', 'active')
      ->get();
    ;
    return response()->json([
      'success' => true,
      'SID' => $SID,
      'SR' => $SR,
      'S' => $S,
    ]);
  }

  public function updateAppraisalQuestions(Request $request, $questionId)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    try {
      $ICques = EditableFormQuestions::find($questionId);

      $validatedData = $request->validate([
        'question' => 'required|string',
      ]);

      // Update the question using the validated data
      $ICques->question = $validatedData['question'];
      $ICques->save();

      return response()->json(['success' => true, 'question' => $ICques, 'message' => 'Question updated successfully']);

    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }

  public function deleteAppraisalQuestions(Request $request, $questionId)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }

    try {
      $ICques = EditableFormQuestions::find($questionId);

      $validatedData = $request->validate([
        'status' => 'required|string',
      ]);

      // Update the question status to "inactive"
      $ICques->status = $validatedData['status'];
      $ICques->save();

      return response()->json(['success' => true, 'question' => $ICques, 'message' => 'Question deleted successfully']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }


  public function addAppraisalQuestions(Request $request)
  {
    if (!session()->has('account_id')) {
      return view('auth.login');
    }
    try {
      $validator = Validator::make($request->all(), [
        'question' => 'required',
        'table_initials' => 'required',
      ], [
        'question.required' => 'Please enter a valid question.',
        'table_initials.required' => 'Please check the table id.'
      ]);

      if ($validator->fails()) {
        return response()->json(['success' => false, 'error' => $validator->errors()->first()]);
      }

      $lastActiveQuestion = EditableFormQuestions::where('status', 'active')->latest('question_order')->first();
      $questionOrder = $lastActiveQuestion ? $lastActiveQuestion->question_order + 1 : 1;

      $ICques = EditableFormQuestions::create([
        'question' => $request->input('question'),
        'form_type' => 'appraisal',
        'table_initials' => $request->input('table_initials'),
        'question_order' => $questionOrder,
        'status' => 'active',
      ]);

      // Return the created question with the question_id
      return response()->json(['success' => true, 'question' => $ICques, 'question_id' => $ICques->question_id]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
  }

}