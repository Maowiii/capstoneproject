<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvalYear extends Model
{
  use HasFactory;

  protected $table = 'evaluation_years';

  protected $primaryKey = 'eval_id';

  protected $dates = ['kra_start', 'kra_end', 'pr_start', 'pr_end', 'eval_start', 'eval_end'];

  protected $fillable = [
    'sy_start',
    'sy_end',
    'kra_start',
    'kra_end',
    'pr_start',
    'pr_end',
    'eval_start',
    'eval_end',
    'status'
  ];

  protected $unique = ['sy_start', 'sy_end'];

  public function scoreWeights()
  {
    return $this->hasOne(ScoreWeights::class, 'eval_id', 'eval_id');
  }
}
