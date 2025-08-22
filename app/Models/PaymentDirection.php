<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDirection extends Model 
{
    use HasFactory;
    protected $table = 'payment_directions'; 
    
    protected $fillable =[
        'payment_directtion',
        'phone',
        'email',
        
    ];
   
}
