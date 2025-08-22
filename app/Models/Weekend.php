<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weekend extends Model
{
    use HasFactory;
    protected $table = 'weekends'; 
    
    protected $fillable =[
        'weekend_name',
        'subscriber_id',
    ];
    
    public $timestamps = true;

    public function storeVat(){
        return $this->hasMany(StoreVat::class);
    }
}
