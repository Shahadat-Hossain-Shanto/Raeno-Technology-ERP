<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaf extends Model
{
    use HasFactory;
    protected $table = 'leaves';

    protected $fillable =[
        'leaf_type',
        'total_number_of_per_box',
        'created_by',
        'updated_by',
        'subscriber_id',
        'user_id'
    ];
    public $timestamps = true;
}
