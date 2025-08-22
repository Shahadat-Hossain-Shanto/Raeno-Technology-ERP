<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyKpiSlab extends Model
{
    protected $table = 'monthly_kpi_slabs';

    protected $fillable = [
        'monthly_kpi_id',
        'criteria_percent',
        'incentive_rate',
    ];

    public function monthlyKpi()
    {
        return $this->belongsTo(MonthlyKpi::class, 'monthly_kpi_id');
    }
}
