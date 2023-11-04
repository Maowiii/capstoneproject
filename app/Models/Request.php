<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'request_id'; // Specify the primary key

    protected $fillable = [
        'appraisal_id',
        'request',
        'status',
        'action',
    ];

    public function appraisal(): BelongsTo
    {
        return $this->belongsTo(Appraisals::class, 'appraisal_id');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $activeEvaluationYear = EvalYear::where('status', 'active')->first();
        if ($activeEvaluationYear) {
        $activeYear = 'kras_' . $activeEvaluationYear->sy_start . '_' . $activeEvaluationYear->sy_end;
        $this->setTable($activeYear);
        }
    }
}
