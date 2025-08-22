<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelKpi extends Model
{
    protected $table = 'model_kpi';

    protected $fillable = [
        'model_id',
        'model_name',
        'kpi_for',
        'target_quantity',
        'month_year',
    ];

    public function slabs()
    {
        return $this->hasMany(ModelKpiSlab::class, 'model_kpi_id');
    }
}
