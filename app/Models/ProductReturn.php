<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $table = 'product_return';

    protected $fillable = [
        // 'order_id',
        'distributor_id',
        'distributor_name',
        'mobile',
        'quantity',
        'medium',
        'note',
        'created_by',
        'status',
        'posting_status',
        'receive_date',
    ];

    public function details()
    {
        return $this->hasMany(ProductReturnDetail::class, 'return_id');
    }

    public function quantities()
    {
        return $this->hasMany(ProductReturnQuantity::class, 'return_id');
    }
}

