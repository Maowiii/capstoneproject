<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EvalYear;

trait BindsDynamically
{
  protected $connection = null;

  public function bind(string $connection, string $table)
  {
    $this->setConnection($connection);
    $this->setTable($table);
  }

  public function newInstance($attributes = [], $exists = false)
  {
    $model = new static((array) $attributes);
    $model->exists = $exists;
    $model->setTable(
      $this->getTable()
    );
    $model->setConnection(
      $this->getConnectionName()
    );

    return $model;
  }
}

class Signature extends Model
{
  use HasFactory;
  use BindsDynamically;
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