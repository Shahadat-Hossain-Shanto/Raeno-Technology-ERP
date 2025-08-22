<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subscriber;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers'; 
    
    protected $fillable =[
        'supplier_name',
        'contact_number',
        'supplier_email',
        'supplier_website',
        'supplier_address',
        'note',
        'created_by',
        'updated_by',
        'subscriber_id',
    ];
    public $timestamps = true;

    public function subscriber(){
        return $this->belongsTo(Subscriber::class);
    }
}
