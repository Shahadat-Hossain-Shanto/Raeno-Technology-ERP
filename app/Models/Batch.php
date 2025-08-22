<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $table = 'batches';

    protected $fillable =[
        'batch_number',
        'expiry_date',
        'created_by',
        'updated_by',
        'subscriber_id',
        'user_id'
    ];
    public $timestamps = true;
}
