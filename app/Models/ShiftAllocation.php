<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftAllocation extends Model
{
    use HasFactory;
    protected $table = 'shift_allocations';

    protected $fillable =[
        'employee_id',
        'shift_name',
        'start_date',
        'end_date',
        'subscriber_id',
    ];
    public $timestamps = true;
}
