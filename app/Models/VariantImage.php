<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantImage extends Model
{
    use HasFactory;
    protected $table = 'variant_images'; 
    
    protected $fillable =[
        'image_name',
        'image_size',
        'image_extension',
        'subscriber_id',
    ];
    public $timestamps = true;
}
