<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\EvalYear;

class Appraisals extends Model
{
    use HasFactory;

    protected $primaryKey = 'appraisal_id';
    public $timestamps = false;

    protected $fillable = [
        'evaluation_type',
        'employee_id',
        'evaluator_id',
        'date_submitted',
        'signature',
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

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employee_id')->with('department');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'evaluator_id')->with('department');
    }
}

?>