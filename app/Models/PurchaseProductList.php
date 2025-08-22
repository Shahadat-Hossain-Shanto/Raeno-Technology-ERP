<?php

namespace App\Models;

use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseProductList extends Model
{
    use HasFactory;
    protected $table = 'purchase_product_lists';

    protected $fillable =[
        'productId',
        'productName',
        'quantity',
        'recieve_quantity',
        'unitPrice',
        'totalPrice',
        'created_by',
        'updated_by',
        'purchaseProductId',
        'variant_id',
        'variant_name',
        'mrp',
    ];

    public $timestamps = true;

    public function purchaseProduct(){
        return $this->belongsTo(PurchaseProduct::class);
    }
}
