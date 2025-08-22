<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'imei_1',
        'imei_2',
        'serial_number',
        'product_name',
        'model',
        'variant',
        'brand',
        'manufacturer',
        'price',
        'order_id',
        'distributor_id',
        'delivery_id',

    ];
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
}

