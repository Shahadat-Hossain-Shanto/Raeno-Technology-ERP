<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelKpiSlab extends Model
{
    protected $table = 'model_kpi_slabs';

    protected $fillable = [
        'model_kpi_id',
        'criteria_percent',
        'incentive_amount',
    ];

    public function modelKpi()
    {
        return $this->belongsTo(ModelKpi::class, 'model_kpi_id');
    }
}
