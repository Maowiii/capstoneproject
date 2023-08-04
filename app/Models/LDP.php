<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LDP extends Model
{
    use HasFactory;
    protected $table = 'learning_development_plans_2023_2024';

    protected $primaryKey = 'development_plan_id';

    public $timestamps = false;

    protected $fillable = [
        'appraisal_id',
        'learning_need',
        'methodology',
        'development_plan_order',
    ];
    
    public function appraisal(): BelongsTo
    {
        return $this->belongsTo(Appraisals::class, 'appraisal_id');
    }
}
