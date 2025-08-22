<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;

class Subcategory extends Model
{
    use HasFactory;
    protected $table = 'subcategories'; 
    
    protected $fillable =[
        'subcategory_name',
        'created_by',
        'updated_by',
        'category_id',
    ];
    public $timestamps = true;

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
