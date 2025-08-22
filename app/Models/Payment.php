<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments'; 
    
    protected $fillable =[
        'paymentId',
        'amount',
        'type',
        'created_by',
        'updated_by',
    ];
    public $timestamps = true;
}
