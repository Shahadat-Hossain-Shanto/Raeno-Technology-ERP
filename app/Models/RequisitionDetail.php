<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'requisition_id',
        'product_details',
        'product_name',
        'model',
        'variant',
        'quantity',
        'rate',
        'amount',
        'rebate',
        'rebat_type',
        'total_amount',
        'product_id',
        'variant_id',
    ];
}
