<?php

namespace App\Http\Controllers\ImmediateSuperior;

use App\Http\Controllers\Controller;
use App\Models\KRA;
use App\Models\WPP;
use App\Models\LDP;
use App\Models\JIC;
use App\Models\Appraisals;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ISAppraisalController extends Controller
{
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
    $appraisalId = null;

    // Loop through the appraisal records to find the correct appraisal type and evaluator data
    foreach ($appraisals as $appraisal) {
      // Fetch the appraisee data based on the $employee_id
      $appraisee = Employees::find($appraisal->employee_id);

      // Determine the appraisal type
      $appraisalType = $appraisal->appraisal_type;

      // Handle different appraisal types
      if ($appraisalType === 'self evaluation') {
        // For self-evaluation, appraiser and appraisee are the same
        $evaluator = $appraisee;
        $appraisalId = $appraisal->appraisal_id;
      } elseif ($appraisalType === 'internal customer 1' || $appraisalType === 'internal customer 2') {
        // For internal customer evaluation, fetch the evaluator data based on the evaluator_id
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisalId = $appraisal->appraisal_id;
      } elseif ($appraisalType === 'is evaluation') {
        // For immediate superior evaluation, fetch the evaluator data based on the evaluator_id
        $evaluator = Employees::find($appraisal->evaluator_id);
        $appraisalId = $appraisal->appraisal_id;
      }
      break; // Exit the loop after finding the first matching appraisal
    }

    // Return the view with appraisee, evaluator, and appraisal ID data
    return view('is-pages.is_appraisal', ['appraisee' => $appraisee, 'evaluator' => $evaluator, 'appraisalId' => $appraisalId]);
  }

  public function saveISAppraisal(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'kra' => 'required|string',
      'kra_weight' => 'required|numeric',
      'objective' => 'required|string',
      'performance_indicator' => 'required|string',

      'continue_doing' => 'required|string',
      'stop_doing' => 'required|string',
      'start_doing' => 'required|string',

      'learning_need' => 'required|string',
      'methodology' => 'required|string',

      'feedback.*.question' => 'required|string',
      'feedback.*.answer' => 'required|numeric',
      'feedback.*.comment' => 'required|string',

    ], [
      'kra.required' => 'Please enter a valid KRA.',
      'kra_weight.required' => 'Please enter a valid KRA weight.',
      'objective.required' => 'Please enter a KRA objective.',
      'performance_indicator.required' => 'Please enter a performance indicator.',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
      KRA::create([
        'appraisal_id' => '1',
        'kra_order' => '1',
        'kra' => $request->input('kra'),
        'kra_weight' => $request->input('kra_weight'),
        'objective' => $request->input('objective'),
        'performance_indicator' => $request->input('performance_indicator')
      ]);

      WPP::create([
        'appraisal_id' => '1',
        'continue_doing' => $request->input('continue_doing'),
        'stop_doing' => $request->input('stop_doing'),
        'start_doing' => $request->input('start_doing'),
        'performance_plan_order' => '1'
      ]);

      LDP::create([
        'appraisal_id' => '1',
        'learning_need' => $request->input('learning_need'),
        'methodology' => $request->input('methodology'),
        'development_plan_order' => '1'
      ]);

      $feedbackData = $request->input('feedback');
      foreach ($feedbackData as $questionNumber => $data) {
        JIC::create([
          'appraisal_id' => 1,
          // Replace with the correct appraisal ID based on your application logic
          'job_incumbent_question' => $data['question'],
          'answer' => $data['answer'],
          'comments' => $data['comment'],
        ]);
      }
    }

    return redirect()->route('viewISAppraisalsOverview')->with('success', 'KRA data saved successfully!');

  }


  public function getAppraisalSE($employee_id)
  {

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
}
?>