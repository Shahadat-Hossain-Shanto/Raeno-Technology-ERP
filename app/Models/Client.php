<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients'; 
    
    protected $fillable =[
        'mobile',
        'name',
        'type',
        'email',
        'address',
        'note',
        'storeId',
        'image',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;
}
