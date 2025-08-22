<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\StoreVat;

class Vat extends Model
{
    use HasFactory;
    protected $table = 'vats'; 
    
    protected $fillable =[
        'taxId',
        'taxName',
        'taxAmount',
        'vatType',
        'vatOption',
        'store',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
    
    public $timestamps = true;

    public function storeVat(){
        return $this->hasMany(StoreVat::class);
    }
}
