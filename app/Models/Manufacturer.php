<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $table = 'manufacturers';

    protected $fillable = [
        'manufacturer_name',
        'manufacturer_logo',
        'manufacturer_origin',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
}
