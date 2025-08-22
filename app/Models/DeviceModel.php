<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    use HasFactory;

    protected $table = 'models';

    protected $fillable = [
        'model_name',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
}

