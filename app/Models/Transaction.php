<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable =[
        'debit',
        'credit',
        'balance',
        'head_name',
        'head_code',
        'head_type',
        'date',
        'transaction_id',
        'reference_note',
        'reference_id',
        'subscriber_id',
        'store_id',
        'transaction_type',
        'voucher'
    ];
    public $timestamps = true;
}
