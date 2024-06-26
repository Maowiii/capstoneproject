<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class AppraisalAnswers extends BaseModel
{
  use HasFactory;

  protected $primaryKey = 'appraisal_answer_id';
  public $timestamps = false;

  protected $fillable = [
    'appraisal_id',
    'question_id',
    'score'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'appraisal_answers_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }

  public function appraisal(): BelongsTo
  {
    return $this->belongsTo(Appraisals::class, 'appraisal_id');
  }

  public function question(): BelongsTo
  {
    return $this->belongsTo(FormQuestions::class, 'question_id');
  }

  public static function tableExists()
  {
    $tableName = (new static)->getTable();

    return Schema::hasTable($tableName);
  }
}

