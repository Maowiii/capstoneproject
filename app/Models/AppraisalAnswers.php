<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AppraisalAnswers extends Model
{
    use HasFactory;

    protected $table = 'appraisal_answers_2023_2024';
    protected $primaryKey = 'appraisal_answer_id';
    public $timestamps = false;

    protected $fillable = [
        'appraisal_id',
        'question_id',
        'score'
    ];

    public function appraisal(): BelongsTo
    {
        return $this->belongsTo(Appraisals::class, 'appraisal_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(FormQuestions::class, 'question_id');
    }
}
