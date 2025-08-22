<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses'; 
    
    protected $fillable =[
        'expense_type',
        'amount',
        'note',
        'image',
        'created_by',
        'updated_by',
        'submitted_by',
        'subscriber_id',
        'store_id'
    ];
    
    public $timestamps = true;
}
