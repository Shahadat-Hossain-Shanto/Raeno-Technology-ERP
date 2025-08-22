<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryInHistory extends Model
{
    use HasFactory;
    protected $table = 'primary_in_histories';

    protected $fillable = [
        'product_name',
        'quantity',
        'unit_price',
        'created_by',
        'updated_by',
        'subscriber_id',
        'variant_name',
        'brand',
        'model',
        'manufacturer',
        'created_at',
    ];

    public $timestamps = true;

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

