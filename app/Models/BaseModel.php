<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class BaseModel extends Model
{
  use BindsDynamically;
  use HasFactory;
}