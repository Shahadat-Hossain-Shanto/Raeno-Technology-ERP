<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subscriber;
use App\Models\Product;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands'; 
    
    protected $fillable =[
        'brand_name',
        'brand_logo',
        'brand_origin',
    ];
    public $timestamps = true;

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
