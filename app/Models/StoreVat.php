<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vat;

class StoreVat extends Model
{
    use HasFactory;
    protected $table = 'store_vats'; 
    
    protected $fillable =[
        'taxId',
        'taxName',
        'taxAmount',
        'vatType',
        'vatOption',
        'storeId',
        'vatId',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    public function vat(){
        return $this->belongsTo(Vat::class);
    }
}
