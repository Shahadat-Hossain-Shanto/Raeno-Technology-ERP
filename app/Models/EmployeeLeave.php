<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;
    protected $table = 'employee_leaves'; 
    
    protected $fillable =[
        'employee_id',
        'employee_name',
        'leave_type',
        'start_date',
        'end_date',
        'note',
        'leave_status',
        'subscriber_id',
        'created_by_user',
        'updated_by_user',
    ];
    
    public $timestamps = true;
}
