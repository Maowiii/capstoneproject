<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LDP extends Model
{
  use HasFactory;

  protected $primaryKey = 'development_plan_id';

  public $timestamps = false;

  protected $fillable = [
    'appraisal_id',
    'learning_need',
    'methodology',
    'development_plan_order',
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'learning_development_plans_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }

  public function appraisal(): BelongsTo
  {
    return $this->belongsTo(Appraisals::class, 'appraisal_id');
  }
}