<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subscriber;
use App\Models\Subcategory;


class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; 
    
    protected $fillable =[
        'subcategory_name',
        'created_by',
        'updated_by',
        'category_id',
    ];
    public $timestamps = true;

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }

    public function subcategory(){
        return $this->hasMany(Subcategory::class);
    }
}
