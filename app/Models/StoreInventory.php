<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInventory extends Model
{
    use HasFactory;
    protected $table = 'store_inventories';

    protected $fillable =[
        'onHand',
        'productIncoming',
        'productOutgoing',
        'safety_stock',
        'mrp',
        'price',
        'created_by',
        'updated_by',
        'productId',
        'store_id',
        'variant_id',
        'variant_name',
    ];
    public $timestamps = true;
}
