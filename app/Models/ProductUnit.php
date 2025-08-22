<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;
    protected $table = 'product_units'; 
    
    protected $fillable =[
        'name',
        'description',
        'created_by',
        'updated_by',
        'subscriber_id',
        'user_id'
    ];
    
    public $timestamps = true;
}
