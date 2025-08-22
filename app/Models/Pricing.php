<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{    

    protected $table = 'pricing';

    protected $fillable = [
        'product_id', 'product_name', 'variant_id', 'variant_name',
        'landed_cost', 'dealer_cost', 'vat_tax', 'model',
        'manufacturer', 'brand', 'created_by', 'updated_by',
    ];

    public function creator()
    {
    return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
    return $this->belongsTo(User::class, 'updated_by');
    }

    use HasFactory;
}