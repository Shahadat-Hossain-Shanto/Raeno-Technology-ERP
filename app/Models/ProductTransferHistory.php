<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransferHistory extends Model
{
    use HasFactory;
    protected $table = 'product_transfer_histories'; 
    
    protected $fillable =[
        'store',
        'product',
        'quantity',
        'unit_price',
        'mrp',
        'created_by',
        'updated_by',
        'subscriber_id',
        'user_id'
    ];
    public $timestamps = true;
}
