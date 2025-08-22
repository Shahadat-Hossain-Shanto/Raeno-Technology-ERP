<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientImage extends Model
{
    use HasFactory;
    protected $table = 'client_images'; 
    
    protected $fillable =[
        'imageName',
        'extension',
        'size',
    ];

    public $timestamps = true;
}
