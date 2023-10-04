<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalScores extends Model
{
    use HasFactory;

  protected $primaryKey = 'score_id';
  public $timestamps = false;

  protected $fillable = [
    'employee_id',
    'question_id',
    'department_id',
    'final_score'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'final_scores_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }

  public function employee()
  {
    return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
  }
}
