<?php

namespace App\Observers;

use App\Models\ProductSerial;
use Illuminate\Support\Facades\Auth;
class ProductSerialObserver
{
    /**
     * Handle the ProductSerial "created" event.
     *
     * @param  \App\Models\ProductSerial  $productSerial
     * @return void
     */
    public function creating(ProductSerial $productSerial)
    {
        if(session()->has('subscriberId')){
            $productSerial->created_by = Session('subscriberId');
        }
        else{
            $productSerial->created_by = Auth::user()->subscriber_id;}
    }

    /**
     * Handle the ProductSerial "updated" event.
     *
     * @param  \App\Models\ProductSerial  $productSerial
     * @return void
     */
    public function updating(ProductSerial $productSerial)
    {
        if(session()->has('subscriberId')){
            $productSerial->updated_by = Session('subscriberId');
        }
        else{
            $productSerial->updated_by = Auth::user()->subscriber_id;}
    }
}
