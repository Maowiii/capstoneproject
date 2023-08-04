<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class JIC extends Model
{
    use HasFactory;
    protected $table = 'job_incumbents_2023_2024';

    protected $primaryKey = 'job_incumbent_id';

    public $timestamps = false;

    protected $fillable = [
        'appraisal_id',
        'job_incumbent_question',
        'answer',
        'comments'
    ];
    
    public function appraisal(): BelongsTo
    {
        return $this->belongsTo(Appraisals::class, 'appraisal_id');
    }
}
