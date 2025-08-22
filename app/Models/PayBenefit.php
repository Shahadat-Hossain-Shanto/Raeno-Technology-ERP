<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBenefit extends Model
{
    use HasFactory;
    protected $table = 'pay_benefits'; 
    
    protected $fillable =[
        'employee_id',
        'employee_name',
        'designation',
        'department',
        'benefit_name',
        'benefit_id',
        'amount',
        'year',
        'subscriber_id',
    ];
    public $timestamps = true;
}
