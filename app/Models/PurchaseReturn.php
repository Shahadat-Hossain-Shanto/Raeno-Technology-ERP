<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;
    protected $table = 'purchase_returns'; 
    
    protected $fillable =[
        'po_no',
        'total_price',
        'other_cost',
        'total_deduction',
        'product_id',
        'product_name',
        'return_qty',
        'price',
        'deduction',
        'note',
        'subscriber_id',
        'user_id',
        'store_id',
        'return_number'
    ];
    
    public $timestamps = true;
}
