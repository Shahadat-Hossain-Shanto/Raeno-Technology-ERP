<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryStockIn extends Model
{
    use HasFactory;

    protected $table = 'primary_stock_in';

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
        'created_by',
        'status',
    ];
    public function primaryUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
