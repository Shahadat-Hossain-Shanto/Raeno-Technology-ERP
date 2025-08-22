<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\OrderedProductDiscount;

class OrderedProductDiscountObserver
{
    /**
     * Handle the OrderedProductDiscount "created" event.
     *
     * @param  \App\Models\OrderedProductDiscount  $orderedProductDiscount
     * @return void
     */
    public function creating(OrderedProductDiscount $orderedProductDiscount)
    {
        if(session()->has('subscriberId')){
            $orderedProductDiscount->created_by = Session('subscriberId');
        }
        else{
            $orderedProductDiscount->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the OrderedProductDiscount "updated" event.
     *
     * @param  \App\Models\OrderedProductDiscount  $orderedProductDiscount
     * @return void
     */
    public function updating(OrderedProductDiscount $orderedProductDiscount)
    {
        if(session()->has('subscriberId')){
            $orderedProductDiscount->updated_by = Session('subscriberId');
        }
        else{
            $orderedProductDiscount->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the OrderedProductDiscount "deleted" event.
     *
     * @param  \App\Models\OrderedProductDiscount  $orderedProductDiscount
     * @return void
     */
    public function deleted(OrderedProductDiscount $orderedProductDiscount)
    {
        //
    }

    /**
     * Handle the OrderedProductDiscount "restored" event.
     *
     * @param  \App\Models\OrderedProductDiscount  $orderedProductDiscount
     * @return void
     */
    public function restored(OrderedProductDiscount $orderedProductDiscount)
    {
        //
    }

    /**
     * Handle the OrderedProductDiscount "force deleted" event.
     *
     * @param  \App\Models\OrderedProductDiscount  $orderedProductDiscount
     * @return void
     */
    public function forceDeleted(OrderedProductDiscount $orderedProductDiscount)
    {
        //
    }
}
