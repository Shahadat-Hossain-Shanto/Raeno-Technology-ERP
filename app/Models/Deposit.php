<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

     protected $table = 'deposits'; 
    
    protected $fillable =[
        'deposit_date',
        'due',
        'deposit',
        'balance',
        'status',
        'deposit_type',
        'note',
        'image',
        'created_by',
        'updated_by',
        'client_id',
        'store_id',
        'salesBy_id',
        'salesBy_name',
        'subscriber_id'
        

    ];
    public $timestamps = true;
}
