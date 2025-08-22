<?php

namespace App\Models;

use App\Models\PurchaseProductList;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseProduct extends Model
{
    use HasFactory;
    protected $table = 'purchase_products';

    protected $fillable =[
        'supplerId',
        'poNumber',
        'totalPrice',
        'discount',
        'grandTotal',
        'purchaseDate',
        'purchaseNote',
        'created_by1',
        'created_by',
        'updated_by'
    ];

    public $timestamps = true;

    public function purchaseProductList(){
        return $this->hasMany(PurchaseProductList::class);
    }
}
