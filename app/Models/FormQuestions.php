<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormQuestions extends Model
{
  use HasFactory;


  protected $primaryKey = 'question_id';

  protected $fillable = [
    'form_type',
    'table_initials',
    'question',
    'question_order'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'form_questions_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }
}