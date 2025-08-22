<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderedProduct;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders'; 
    
    protected $fillable =[
        'clientId',
        'total',
        'totalDiscount',
        'grandTotal',
        'orderDate',
        'paymentType',
        'totalTax',
        'created_by',
        'updated_by'

    ];
    public $timestamps = true;

    public function orderedProduct(){
        return $this->hasMany(OrderedProduct::class);
    }

}
