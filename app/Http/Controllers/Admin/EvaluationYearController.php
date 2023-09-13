<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvalYear;
use App\Models\Accounts;
use App\Models\Employees;
use App\Models\Appraisals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class EvaluationYearController extends Controller
{
  public function viewEvaluationYears()
  {
    return view('admin-pages.evaluation_years');
  }

  public function displayEvaluationYear()
  {
    $evalyears = EvalYear::all();
    Log::debug($evalyears);
    return response()->json(['success' => true, 'evalyears' => $evalyears]);
  }

  public function addEvalYear(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'sy_start' => [
        'required',
        Rule::unique('evaluation_years', 'sy_start')->where(function ($query) use ($request) {
          return $query->where('sy_end', $request->input('sy_end'));
        }),
      ],
      'sy_end' => [
        'required',
        Rule::unique('evaluation_years', 'sy_end')->where(function ($query) use ($request) {
          return $query->where('sy_start', $request->input('sy_start'));
        }),
      ],
      'kra_start' => 'required',
      'kra_end' => 'required',
      'pr_start' => 'required',
      'pr_end' => 'required',
      'eval_start' => 'required',
      'eval_end' => 'required'
    ], [
      'sy_start.required' => 'Please choose a date for the start of the school year.',
      'sy_end.required' => 'Please choose a date for the end of the school year.',
      'sy_start.unique' => 'The chosen start date for the school year already exists.',
      'sy_end.unique' => 'The chosen end date for the school year already exists.',
      'kra_start.required' => 'Please choose a starting date for kra encoding.',
      'kra_end.required' => 'Please choose an ending date for kra encoding.',
      'pr_start.required' => 'Please choose a starting date for performance review.',
      'pr_end.required' => 'Please choose an ending date for performance review.',
      'eval_start.required' => 'Please choose a starting date for evaluation.',
      'eval_end.required' => 'Please choose an ending date for evaluation.',
    ]);


    if ($validator->fails()) {
      Log::info('Validation Errors:', $validator->errors()->all());

      return response()->json([
        'success' => false,
        'errors' => $validator->errors(),
        'input' => $request->all()
      ]);
    } else {
      return response()->json(['success' => true]);
    }
  }

  public function confirmEvalYear(Request $request)
  {
    EvalYear::where('status', 'active')->update(['status' => 'inactive']);
    $eval_year = EvalYear::create([
      'sy_start' => $request->input('sy_start'),
      'sy_end' => $request->input('sy_end'),
      'kra_start' => $request->input('kra_start'),
      'kra_end' => $request->input('kra_end'),
      'pr_start' => $request->input('pr_start'),
      'pr_end' => $request->input('pr_end'),
      'eval_start' => $request->input('eval_start'),
      'eval_end' => $request->input('eval_end'),
      'status' => 'active'
    ]);

    $sy = '_' . $request->input('sy_start') . '_' . $request->input('sy_end');

    Schema::connection('mysql')->create('appraisals' . $sy, function ($table) {
      $table->bigIncrements('appraisal_id');
      $table->string('evaluation_type');
      $table->integer('employee_id');
      $table->integer('evaluator_id')->nullable();
      $table->date('date_submitted')->nullable();
      $table->boolean('locked')->default(false);
    });

    Schema::connection('mysql')->create('form_questions' . $sy, function ($table) {
      $table->bigIncrements('question_id');
      $table->string('form_type');
      $table->string('table_initials');
      $table->text('question');
      $table->integer('question_order');
      $table->string('status');
      $table->nullableTimestamps();
    });

    Schema::connection('mysql')->create('appraisal_answers' . $sy, function ($table) {
      $table->bigIncrements('appraisal_answer_id');
      $table->integer('appraisal_id')->nullable();
      $table->integer('kra_id')->nullable();
      $table->integer('question_id')->nullable();
      $table->integer('score');
    });

    Schema::connection('mysql')->create('kras' . $sy, function ($table) {
      $table->bigIncrements('kra_id');
      $table->integer('appraisal_id');
      $table->text('kra')->nullable();
      $table->decimal('kra_weight')->nullable();
      $table->text('objective')->nullable();
      $table->text('performance_indicator')->nullable();
      $table->text('actual_result')->nullable();
      $table->decimal('weighted_total')->nullable();
      $table->integer('kra_order');
    });

    Schema::connection('mysql')->create('learning_development_plans' . $sy, function ($table) {
      $table->bigIncrements('development_plan_id');
      $table->integer('appraisal_id');
      $table->text('learning_need')->nullable();
      $table->text('methodology')->nullable();
      $table->integer('development_plan_order');
    });

    Schema::connection('mysql')->create('work_performance_plans' . $sy, function ($table) {
      $table->bigIncrements('performance_plan_id');
      $table->integer('appraisal_id');
      $table->text('continue_doing')->nullable();
      $table->text('stop_doing')->nullable();
      $table->text('start_doing')->nullable();
      $table->integer('performance_plan_order');
    });

    Schema::connection('mysql')->create('comments' . $sy, function ($table) {
      $table->bigIncrements('comment_id');
      $table->integer('appraisal_id');
      $table->text('customer_service')->nullable();
      $table->text('suggestion')->nullable();
    });

    Schema::connection('mysql')->create('job_incumbents' . $sy, function ($table) {
      $table->bigIncrements('job_incumbent_id');
      $table->integer('appraisal_id');
      $table->text('job_incumbent_question');
      $table->text('answer')->nullable();
      $table->integer('comments')->nullable();
      $table->integer('question_order');
    });

    Schema::connection('mysql')->create('signature' . $sy, function ($table) {
      $table->bigIncrements('signature_id');
      $table->integer('appraisal_id');
      $table->binary('sign_data');
      $table->text('sign_type')->nullable();
      $table->nullableTimestamps();
    });

    $originalFormQuestionsTable = 'form_questions';
    $newFormQuestionsTable = 'form_questions' . $sy;

    DB::connection('mysql')->insert("INSERT INTO $newFormQuestionsTable (question_id, form_type, table_initials, question, question_order, status, created_at, updated_at) 
                               SELECT question_id, form_type, table_initials, question, question_order, status, created_at, updated_at 
                               FROM $originalFormQuestionsTable");

    $employeesWithPEAccounts = Employees::whereHas('account', function ($query) {
      $query->where('type', 'PE');
    })->get();

    $evaluationTypes = ['self evaluation', 'is evaluation', 'internal customer 1', 'internal customer 2'];

    foreach ($employeesWithPEAccounts as $employee) {
      foreach ($evaluationTypes as $evaluationType) {
        $evaluatorId = null;

        if ($evaluationType === 'self evaluation') {
          $evaluatorId = $employee->employee_id;
        } elseif ($evaluationType === 'is evaluation') {
          $departmentId = $employee->department_id;
          $isAccount = Accounts::where('type', 'IS')
            ->whereHas('employee', function ($query) use ($departmentId) {
              $query->where('department_id', $departmentId);
            })->first();

          if ($isAccount) {
            $evaluatorId = $isAccount->employee->employee_id;
          }
        }

        Appraisals::create([
          'evaluation_type' => $evaluationType,
          'employee_id' => $employee->employee_id,
          'evaluator_id' => $evaluatorId,
        ]);
      }
    }

    return response()->json(['success' => true]);
  }

  public function toggleEvalYearStatus(Request $request)
  {
    $evalID = $request->input('eval_id');

    $evaluationYear = EvalYear::find($evalID);

    if ($evaluationYear) {
      if ($evaluationYear->status === 'active') {
        $evaluationYear->status = 'inactive';
        $evaluationYear->save();
      } elseif ($evaluationYear->status === 'inactive') {
        EvalYear::where('status', 'active')->update(['status' => 'inactive']);

        $evaluationYear->status = 'active';
        $evaluationYear->save();
      }

      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => false, 'error' => 'Evaluation year not found.']);
    }
  }

}