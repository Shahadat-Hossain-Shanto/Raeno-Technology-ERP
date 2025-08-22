<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturnDetail extends Model
{
    protected $table = 'product_return_details';

    protected $fillable = [
        'imei_1',
        'imei_2',
        'serial_number',
        'product_name',
        'model',
        'variant',
        'brand',
        'manufacturer',
        'order_id',
        'distributor_id',
        'receive_status',
        'return_id',
    ];

    public function return()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
}
