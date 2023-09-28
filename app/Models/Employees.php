<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employees extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $primaryKey = 'employee_id';

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'employee_number',
        'first_name',
        'last_name',
        'department_id',
        'job_title',
        'position',
        'immediate_superior_id'
    ];

    protected $nullable = [
        'department_id', 'job_title',
        'position',
        'immediate_superior_id'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'account_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class, 'department_id')->withDefault();
    }

    public function immediateSuperior(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'immediate_superior_id', 'employee_id');
    }
}
