<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $table = 'sales_returns'; 
    
    protected $fillable =[
        'invoice_no',
        'total_price',
        'total_tax_return',
        'total_deduction',
        'product_id',
        'product_name',
        'return_qty',
        'price',
        'tax_return',
        'deduction',
        'note',
        'created_by',
        'updated_by',
        'subscriber_id',
        'user_id'
    ];
    
    public $timestamps = true;
}
