<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $table = 'leave_types';

    protected $fillable =[
        'leave_type',
        'holiday_included',
        'subscriber_id',
    ];
    public $timestamps = true;
}
