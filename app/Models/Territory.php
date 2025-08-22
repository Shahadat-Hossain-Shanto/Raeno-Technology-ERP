<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    use HasFactory;

    protected $fillable = [
        'territory_name',
        'area_name',
        'region_name',
        'status',
        'created_by',
    ];
}
