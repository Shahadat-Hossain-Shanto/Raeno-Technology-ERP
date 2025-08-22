<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    use HasFactory;
    protected $table = 'employee_departments';

    protected $fillable = [
        'department_name',
        'job_description',
        'subscriber_id'
    ];

    public $timestamps = true;
}
