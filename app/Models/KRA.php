<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
    'performance_level',
    'weighted_total',
    'kra_order'
  ];

  public static function tableExists()
  {
    $tableName = (new static)->getTable();

    return Schema::hasTable($tableName);
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'kras_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }

  public function appraisal()
  {
    return $this->belongsTo(Appraisals::class, 'appraisal_id');
  }
}