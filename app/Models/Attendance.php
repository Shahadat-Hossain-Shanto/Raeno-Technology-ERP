<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';

    protected $fillable =[
        'employee_name',
        'employee_id',
        'designation',
        'department',
        'attendance_date',
        'sign_in',
        'sign_out',
        'stay_time',
        'status',
        'subscriber_id',
    ];
    public $timestamps = true;
}
