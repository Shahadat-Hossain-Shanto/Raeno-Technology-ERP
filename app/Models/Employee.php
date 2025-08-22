<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees'; 
    
    protected $fillable =[
        'empName',
        'empEmail',
        'empMobile',
        'password',
        'role',
        'created_by',
        'updated_by',
    ];
    
    public $timestamps = true;
}
