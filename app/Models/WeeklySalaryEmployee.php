<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklySalaryEmployee extends Model
{
    use HasFactory;
    protected $table = 'weekly_salary_employees'; 
    
    protected $fillable =[
        'employee_id',
        'employee_name',
        'designation',
        'department',
        'present',
        'absent',
        'leave',
        'basic_pay',
        'net_pay',
        'salary_grade_id',
        'salary_date',
        'salary_date_2',
        'addition',
        'deduction',
        'subscriber_id',
    ];
    public $timestamps = true;
}
