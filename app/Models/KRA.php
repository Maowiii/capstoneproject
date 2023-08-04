<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KRA extends Model
{
    use HasFactory;

    protected $table = 'kras_2023_2024';

    protected $primaryKey = 'kra_id';

    public $timestamps = false;

    protected $fillable = [
        'appraisal_id',
        'kra',
        'kra_weight',
        'objective',
        'performance_indicator',
        'actual_result',
        'weighted_total',
        'kra_order'
    ];
}
