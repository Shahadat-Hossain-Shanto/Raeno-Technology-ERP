<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $table = 'shifts';

    protected $fillable =[
        'shift_name',
        'in_time',
        'out_time',
        'subscriber_id',
        'user_id',
    ];
    public $timestamps = true;
}
