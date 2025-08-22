<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'requisition_date',
        'distributor_id',
        'name',
        'address',
        'mobile',
        'quantity',
        'rate',
        'amount',
        'rebate',
        'total_amount',
        'created_by',
        'requisition_note',
        'sales_approved_status',
        'sales_approved_by',
        'sales_approved_date',
        'sales_approved_note',
        'accounts_approved_status',
        'accounts_approved_by',
        'accounts_approved_date',
        'accounts_approved_note',
        'operations_approved_status',
        'operations_approved_by',
        'operations_approved_date',
        'operations_approved_note',
        'status',
        'delivery_status',
        'posting'
    ];

 public function creator()
 {
    return $this->belongsTo(User::class, 'created_by');
 }
}
