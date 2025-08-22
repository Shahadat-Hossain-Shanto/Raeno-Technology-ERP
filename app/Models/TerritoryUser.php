<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerritoryUser extends Model
{
    protected $table = 'territory_user';

    protected $fillable = [
        'user_id',
        'territory_id',
    ];

    public $timestamps = true;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }
}
