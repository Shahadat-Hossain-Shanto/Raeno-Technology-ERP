<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionID extends Model 
{
    use HasFactory;
    protected $table = 'transactionid'; 
    
    protected $fillable =[
        'transactionid',
        'type',
        
    ];
   
}
