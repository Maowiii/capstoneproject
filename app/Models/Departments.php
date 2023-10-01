<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departments extends Model
{
  use HasFactory;

  protected $table = 'departments';

  protected $primaryKey = 'department_id';

  public $timestamps = false;

  protected $fillable = ['department_name'];

  public function employee(): hasMany
  {
    return $this->hasMany(Employees::class, 'department_id');
  }

  public function appraisal(): hasMany
  {
    return $this->hasMany(Appraisals::class, 'department_id');
  }
}