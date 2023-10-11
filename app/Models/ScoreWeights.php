<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreWeights extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $table = 'score_weights';

  protected $primaryKey = 'score_weight_id';

  protected $fillable = [
    'eval_id',
    'self_eval_weight',
    'ic1_weight',
    'ic2_weight',
    'is_weight',
    'bh_weight',
    'kra_weight'
  ];

  public function evalYear()
  {
    return $this->belongsTo(EvalYear::class, 'eval_id', 'eval_id');
  }
}
