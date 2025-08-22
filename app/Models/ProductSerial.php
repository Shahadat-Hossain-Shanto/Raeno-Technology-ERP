<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSerial extends Model
{
    use HasFactory;
    protected $table = 'product_serials';

    protected $fillable =[
        'productId',
        'productName',
        'variantId',
        'variantName',
        'storeId',
        'serialNumber',
        'purchaseId',
        'purchaseDate',
        'saleId',
        'saleDate',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'subscriber_id'
    ];
    public $timestamps = true;
}
