<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;
    protected $table = 'expense_categories'; 
    
    protected $fillable =[
        'expense_type_name',
        'note',
        'created_by',
        'updated_by',
        'subscriber_id',
        'user_id'
        
    ];
    
    public $timestamps = true;
}
