<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositImage extends Model
{
    use HasFactory;
     protected $table = 'deposit_images'; 
    
    protected $fillable =[
        'imageName',
        'extension',
        'size',
    ];

    public $timestamps = true;
}
