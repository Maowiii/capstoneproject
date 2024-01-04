<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Requests extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'request_id'; // Specify the primary key

    protected $fillable = [
        'appraisal_id',
        'request',
        'status',
        'action',
        'feedback',
        'approver_id',
        'deadline_type',
        'deadline'
    ];

    public function appraisal(): BelongsTo
    {
        return $this->belongsTo(Appraisals::class, 'appraisal_id');
    }

    public function approver(): BelongsTo
    
    {
        return $this->belongsTo(Employees::class, 'approver_id')->with('department')->from('employees');
    }

    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);

        $activeEvaluationYear = EvalYear::where('status', 'active')->first();
        if ($activeEvaluationYear) {
            $activeYear = 'form_request_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
            $this->setTable($activeYear);
        }
    }

    public static function tableExists()
    {
        $tableName = (new static)->getTable();

        return Schema::hasTable($tableName);
    }
}