<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuePayment extends Model
{
    use HasFactory;
    protected $table = 'due_payments'; 
    
    protected $fillable =[
        'total',
        'cash',
        'card',
        'mobile_bank',
        'due_amount',
        'created_by',
        'updated_by',
        'clientId'
    ];
    public $timestamps = true;
}
