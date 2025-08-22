<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTable extends Model
{
    use HasFactory;
    protected $table = 'employee_tables';

    protected $fillable = [
        'employee_name',
        'email',
        'phone',
        'designation',
        'employee_type',
        'blood_group',
        'address',
        'image',
        'status',

    ];

    public $timestamps = true;
}
