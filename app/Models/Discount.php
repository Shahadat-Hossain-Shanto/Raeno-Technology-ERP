<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\StoreDiscount;

class Discount extends Model
{
    use HasFactory;
    protected $table = 'discounts'; 
    
    protected $fillable =[
        'discountId',
        'discountName',
        'discountType',
        'discount',
        'isRestricted',
        'created_by',
        'updated_by',
        'store',
        'subscriber_id'
    ];
    public $timestamps = true;

    public function storeDiscount(){
        return $this->hasMany(StoreDiscount::class);
    }
}
