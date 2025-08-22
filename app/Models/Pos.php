<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Store;

class POS extends Model
{
    use HasFactory;
    protected $table = 'p_o_s'; 
    
    protected $fillable =[
        'pos_name',
        'pos_status',
        'pos_pin',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
    public $timestamps = true;

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
