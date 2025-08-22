<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImeiInfo extends Model
{
    use HasFactory;

    protected $table = 'imei_info';

    protected $fillable = [
        'imei_1',
        'imei_2',
        'serial_number',
        'product_name',
        'brand',
        'model',
        'manufacturer',
        'variant',
        'entry_user',
        'primary_user',
        'primary_in',
        'primary_out',
        'primary_state',
        'dealer',
        'dealer_in',
        'dealer_out',
        'dealer_state',
        'retail',
        'retail_in',
        'retail_out',
        'retail_state',
        'product_return',
    ];
    public function entryUser()
    {
        return $this->belongsTo(User::class, 'entry_user');
    }
    public function primaryUser()
    {
        return $this->belongsTo(User::class, 'primary_user');
    }
    public function dealer()
    {
        return $this->belongsTo(Distributor::class, 'dealer');
    }
    public function retail()
    {
        return $this->belongsTo(Retail::class, 'retail');
    }
}
