<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retail extends Model
{
    use HasFactory;

    protected $table = 'retails';

    protected $fillable = [
        'retail_name',
        'owner_name',
        'nid',
        'contact_no',
        'email',
        'retail_address',
        'type',
        'trade_license_no',
        'trade_license_validity',
        'tin',
        'bkash_no',
        'district_id',
        'upazila_id',
        'distributor_id',
    ];

    // protected $dates = [
    //     'trade_license_validity',
    //     'created_at',
    //     'updated_at',
    // ];
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id');
    }
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
}
