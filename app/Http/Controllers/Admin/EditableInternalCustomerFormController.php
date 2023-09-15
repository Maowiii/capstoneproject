<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvalYear;
use App\Models\FormQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EditableInternalCustomerFormController extends Controller
{
  public function displayEditableInternalCustomerForm()
  {
    return view('admin-pages.editable_ic_form');
  }

  public function getICQuestions()
  {
    $ICques = FormQuestions::where('table_initials', 'IC')
      ->where('status', 'active')
      ->get();

    return response()->json(['success' => true, 'ICques' => $ICques]);
  }

  public function updateICQuestions(Request $request, $questionId)
  {
    try {
      $ICques = FormQuestions::find($questionId);

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

  public function deleteICQuestions(Request $request, $questionId)
  {
    try {
      $ICques = FormQuestions::find($questionId);

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

  public function addICQuestions(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'question' => 'required',
      ], [
        'question.required' => 'Please enter a valid question.',
      ]);

      if ($validator->fails()) {
        return response()->json(['success' => false, 'error' => $validator->errors()->first()]);
      }

      $lastActiveQuestion = FormQuestions::where('status', 'active')->latest('question_order')->first();
      $questionOrder = $lastActiveQuestion ? $lastActiveQuestion->question_order + 1 : 1;

      $ICques = FormQuestions::create([
        'question' => $request->input('question'),
        'form_type' => 'internal customer',
        'table_initials' => 'IC',
        'question_order' => $questionOrder,
        'status' => 'active',
      ]);

      // Return the created question with the question_id
      return response()->json(['success' => true, 'question' => $ICques, 'question_id' => $ICques->question_id]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
  }

  public function formChecker()
  {
    $active = EvalYear::where('status', 'active')->first();

    if ($active) {
      return response()->json(['formLocked' => true]);
    } else {
      return response()->json(['formLocked' => false]);
    }
  }
}