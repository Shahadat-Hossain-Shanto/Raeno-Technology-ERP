<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $table = 'salaries'; 
    
    protected $fillable =[
        'employee_id',
        'employee_name',
        'salary_month',
        'amount',
        'note',
        'image'
    ];
    public $timestamps = true;
}
