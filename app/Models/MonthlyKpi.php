<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyKpi extends Model
{
    protected $table = 'monthly_kpi';

    protected $fillable = [
        'kpi_type',
        'kpi_for',
        'target_amount',
        'month_year',
        'quarter',
    ];

    public function slabs()
    {
        return $this->hasMany(MonthlyKpiSlab::class, 'monthly_kpi_id');
    }
}

