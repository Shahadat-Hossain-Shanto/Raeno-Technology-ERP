<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventories';

    protected $fillable =[
        'onHand',
        'productIncoming',
        'productOutgoing',
        'mrp',
        'price',
        'created_by',
        'updated_by',
        'productId',
        'variant_id',
        'variant_name',
        'safety_stock',
        'subscriber_id',
    ];
    public $timestamps = true;

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
