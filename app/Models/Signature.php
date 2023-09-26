<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EvalYear;

class Signature extends BaseModel
{
  use HasFactory;

  protected $primaryKey = 'signature_id';

  protected $fillable = [
    'appraisal_id',
    'sign_data',
    'sign_type'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $activeEvaluationYear = EvalYear::where('status', 'active')->first();
    if ($activeEvaluationYear) {
      $activeYear = 'signature_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }
  public function appraisal()
  {
    return $this->belongsTo(Appraisals::class, 'appraisal_id');
  }
}