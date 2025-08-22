<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscriber;
use App\Models\Pos;
use App\Models\User;


class Store extends Model
{
    use HasFactory;
    protected $table = 'stores'; 
    
    protected $fillable =[
        'store_name',
        'store_address',
        'contact_number',
        'subscriber_id',
    ];
    public $timestamps = true;

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }

    public function pos(){
        return $this->hasMany(Pos::class);
    }

    // public function user(){
    //     return $this->belongsTo(User::class);
    // }

    public function user(){
        return $this->hasMany(User::class);
    }
}
