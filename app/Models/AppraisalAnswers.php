<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BindsDynamically
{
  protected $connection = null;

  public function bind(string $connection, string $table)
  {
    $this->setConnection($connection);
    $this->setTable($table);
  }

  public function newInstance($attributes = [], $exists = false)
  {
    $model = new static((array) $attributes);
    $model->exists = $exists;
    $model->setTable(
      $this->getTable()
    );
    $model->setConnection(
      $this->getConnectionName()
    );

    return $model;
  }
}

class AppraisalAnswers extends Model
{
  use HasFactory;
  use BindsDynamically;

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
}