<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = 'banks';

    protected $fillable =[
        'bank_name',
        'account_name',
        'account_number',
        'branch',
        'account_head',
        'balance',
        'status',
        'sign_cheque_image',
        'subscriber_id',
        'user_id',
    ];
    public $timestamps = true;
}
