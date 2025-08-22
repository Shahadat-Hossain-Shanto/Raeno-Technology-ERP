<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignUpRequest extends Model
{
    use HasFactory;
    protected $table = 'sign_up_requests';

    protected $fillable =[
        'name',
        'store_name',
        'store_count',
        'business_type',
        'mobile',
        'package',
        'address'
    ];
    public $timestamps = true;
}
