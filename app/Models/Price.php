<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $table = 'prices'; 
    
    protected $fillable =[
        'productId',
        'price',
        'mrp',
        'quantity',
        'category_name',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
    public $timestamps = true;
}
