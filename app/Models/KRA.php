<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KRA extends Model
{
  use HasFactory;
  protected $primaryKey = 'kra_id';

  public $timestamps = false;

  protected $fillable = [
    'appraisal_id',
    'kra',
    'kra_weight',
    'objective',
    'performance_indicator',
    'actual_result',
    'weighted_total',
    'kra_order'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'kras_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }
}
