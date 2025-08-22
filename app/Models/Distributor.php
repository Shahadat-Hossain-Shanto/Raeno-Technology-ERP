<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $table = 'distributor';

    protected $fillable = [
        'distributor_name',
        'owner_name', 'nid',
        'contact_no', 'email',
        'address',
        'business_type',
        'district_id',
        'region_id',
        'area_id',
        'territory_id',
        'trade_license_no',
        'trade_license_validity',
        'tin',
        'bank_name',
        'branch',
        'account_name',
        'account_no',
        'credit_limit',
        'existing_distributor_brands',
        'balance',
        'head_code',
        'distributor_status'
    ];

    public function imeiInfos()
    {
        return $this->hasMany(ImeiInfo::class, 'dealer');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function territory()
    {
        return $this->belongsTo(Territory::class,'territory_id','id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

}
