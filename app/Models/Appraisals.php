<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\EvalYear;
use Illuminate\Support\Facades\Schema;


class Appraisals extends BaseModel
{
  use HasFactory;

  protected $primaryKey = 'appraisal_id';
  public $timestamps = false;

  protected $fillable = [
    'evaluation_type',
    'employee_id',
    'evaluator_id',
    'department_id',
    'eula',
    'bh_score',
    'kra_score',
    'ic_score',
    'date_submitted',
    'kra_locked',
    'pr_locked',
    'eval_locked',
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

  public function department(): BelongsTo
  {
    return $this->belongsTo(Departments::class, 'department_id')->withDefault();
  }
}

?>