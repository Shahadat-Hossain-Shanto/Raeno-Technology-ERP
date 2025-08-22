<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use HasFactory;

    protected $table = 'upazilas';

    protected $fillable = [
        'district_id',
        'name',
    ];

    public $timestamps = false;

    // public function district()
    // {
    //     return $this->belongsTo(District::class);
    // }
}
