<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WPP extends Model
{
    use HasFactory;

    protected $table = 'work_performance_plans_2023_2024';

    protected $primaryKey = 'performance_plan_id';

    public $timestamps = false;

    protected $fillable = [
        'appraisal_id',
        'continue_doing',
        'stop_doing',
        'start_doing',
        'performance_plan_order',
    ];
    
    public function appraisal(): BelongsTo
    {
        return $this->belongsTo(Appraisals::class, 'appraisal_id');
    }
}
