<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorIn extends Model
{
    use HasFactory;

    protected $table = 'distributor_in';

    protected $fillable = [
        'imei_1',
        'imei_2',
        'serial_number',
        'product_name',
        'brand',
        'model',
        'manufacturer',
        'variant',
        'price',
        'distributor_id',
        'distributor_name',
        'distributor_out',
        'received_by',
        'order_id',
        'delivery_id',
        'status',
        'retail_id',
        'retail_status',
        'retail_out',
    ];

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    public function retail()
    {
        return $this->belongsTo(Retail::class, 'retail_id');
    }
    public function distributor(){
        return $this->belongsTo(Distributor::class);
    }
}
