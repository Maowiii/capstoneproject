<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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

class FormQuestions extends Model
{
  use HasFactory;
  use BindsDynamically;

  protected $primaryKey = 'question_id';

  protected $fillable = [
    'form_type',
    'table_initials',
    'question',
    'question_order'
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
      $activeYear = 'form_questions_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
      $this->setTable($activeYear);
    }
  }
}