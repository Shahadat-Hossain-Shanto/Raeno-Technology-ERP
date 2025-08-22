<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'order_id',
        'distributor_id',
        'distributor_name',
        'mobile',
        'quantity',
        'created_by',
        'medium',
        'note',
        'receive_date',
        'status',
    ];
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'order_id', 'requisition_id');
    }
}
