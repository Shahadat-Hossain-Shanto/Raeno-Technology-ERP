<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseImage extends Model
{
    use HasFactory;
    protected $table = 'expense_images'; 
    
    protected $fillable =[
        'imageName',
        'extension',
        'size',
        'expense_id'
    ];

    public $timestamps = true;
}
