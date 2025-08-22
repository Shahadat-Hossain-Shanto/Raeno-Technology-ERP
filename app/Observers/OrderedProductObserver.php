<?php

namespace App\Observers;

use App\Models\OrderedProduct;
use Illuminate\Support\Facades\Auth;


class OrderedProductObserver
{
    /**
     * Handle the OrderedProduct "created" event.
     *
     * @param  \App\Models\OrderedProduct  $orderedProduct
     * @return void
     */
    public function creating(OrderedProduct $orderedProduct)
    {
        if(session()->has('subscriberId')){
            $orderedProduct->created_by = Session('subscriberId');
        }
        else{
            $orderedProduct->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the OrderedProduct "updated" event.
     *
     * @param  \App\Models\OrderedProduct  $orderedProduct
     * @return void
     */
    public function updating(OrderedProduct $orderedProduct)
    {
        if(session()->has('subscriberId')){
            $orderedProduct->updated_by = Session('subscriberId');
        }
        else{
            $orderedProduct->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the OrderedProduct "deleted" event.
     *
     * @param  \App\Models\OrderedProduct  $orderedProduct
     * @return void
     */
    public function deleted(OrderedProduct $orderedProduct)
    {
        //
    }

    /**
     * Handle the OrderedProduct "restored" event.
     *
     * @param  \App\Models\OrderedProduct  $orderedProduct
     * @return void
     */
    public function restored(OrderedProduct $orderedProduct)
    {
        //
    }

    /**
     * Handle the OrderedProduct "force deleted" event.
     *
     * @param  \App\Models\OrderedProduct  $orderedProduct
     * @return void
     */
    public function forceDeleted(OrderedProduct $orderedProduct)
    {
        //
    }
}
