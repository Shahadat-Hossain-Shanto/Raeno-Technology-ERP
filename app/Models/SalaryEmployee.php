<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryEmployee extends Model
{
    use HasFactory;
    protected $table = 'salary_employees'; 
    
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
        'salary_month',
        'addition',
        'deduction',
        'subscriber_id',
    ];
    public $timestamps = true;
}
