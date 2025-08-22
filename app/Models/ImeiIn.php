<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImeiIn extends Model
{
    use HasFactory;

    protected $table = 'imei_in';

    protected $fillable = [
        'imei_1',
        'imei_2',
        'serial_number',
        'product_name',
        'model',
        'created_by',
        'varient',
    ];
}
