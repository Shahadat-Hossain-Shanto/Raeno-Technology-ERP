<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['id', 'name'];

    public function distributors()
    {
        return $this->hasMany(Distributor::class);
    }
}
