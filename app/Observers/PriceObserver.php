<?php

namespace App\Observers;

use App\Models\Price;
use Illuminate\Support\Facades\Auth;


class PriceObserver
{
    /**
     * Handle the Price "created" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function creating(Price $price)
    {
        if(session()->has('subscriberId')){
            $price->created_by = Session('subscriberId');
        }
        else{
            $price->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Price "updated" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function updating(Price $price)
    {
        if(session()->has('subscriberId')){
            $price->updated_by = Session('subscriberId');
        }
        else{
            $price->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Price "deleted" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function deleted(Price $price)
    {
        //
    }

    /**
     * Handle the Price "restored" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function restored(Price $price)
    {
        //
    }

    /**
     * Handle the Price "force deleted" event.
     *
     * @param  \App\Models\Price  $price
     * @return void
     */
    public function forceDeleted(Price $price)
    {
        //
    }
}
