<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Discount;

class StoreDiscount extends Model
{
    use HasFactory;
    protected $table = 'store_discounts'; 
    
    protected $fillable =[
        'discountId',
        'discountName',
        'discountType',
        'discount',
        'isRestricted',
        'storeId',
        'discountId',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    public function discount(){
        return $this->belongsTo(Discount::class);
    }
}
