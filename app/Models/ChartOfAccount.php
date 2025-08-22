<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts'; 

    protected $fillable =[
        'head_code',
        'head_name',
        'parent_head',
        'parent_head_level',
        'head_type',
        'is_transaction',
        'is_active',
        'is_general_ledger',
        'subscriber_id',
    ];
    public $timestamps = true;
}
