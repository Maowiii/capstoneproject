<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;


class AdminAppraisals extends BaseModel
{
  use HasFactory;

  protected $primaryKey = 'appraisal_id';
  public $timestamps = false;

  protected $fillable = [
    'evaluation_type',
    'employee_id',
    'evaluator_id',
    'date_submitted',
    'signature',
    'locked'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'appraisals_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }

  public static function tableExists()
  {
    $tableName = (new static)->getTable();

    return Schema::hasTable($tableName);
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employees::class, 'employee_id')->with('department')->from('employees');
  }

  public function evaluator(): BelongsTo
  {
    return $this->belongsTo(Employees::class, 'evaluator_id')->with('department')->from('employees');
  }

  public function signatures()
  {
    return $this->hasOne(Signature::class, 'appraisal_id');
  }
}

?>