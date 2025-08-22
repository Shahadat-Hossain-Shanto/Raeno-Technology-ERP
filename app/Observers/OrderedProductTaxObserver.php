<?php

namespace App\Observers;

use App\Models\OrderedProductTax;
use Illuminate\Support\Facades\Auth;

class OrderedProductTaxObserver
{
    /**
     * Handle the OrderedProductTax "created" event.
     *
     * @param  \App\Models\OrderedProductTax  $orderedProductTax
     * @return void
     */
    public function creating(OrderedProductTax $orderedProductTax)
    {
        if(session()->has('subscriberId')){
            $orderedProductTax->created_by = Session('subscriberId');
        }
        else{
            $orderedProductTax->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the OrderedProductTax "updated" event.
     *
     * @param  \App\Models\OrderedProductTax  $orderedProductTax
     * @return void
     */
    public function updating(OrderedProductTax $orderedProductTax)
    {
        if(session()->has('subscriberId')){
            $orderedProductTax->updated_by = Session('subscriberId');
        }
        else{
            $orderedProductTax->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the OrderedProductTax "deleted" event.
     *
     * @param  \App\Models\OrderedProductTax  $orderedProductTax
     * @return void
     */
    public function deleted(OrderedProductTax $orderedProductTax)
    {
        //
    }

    /**
     * Handle the OrderedProductTax "restored" event.
     *
     * @param  \App\Models\OrderedProductTax  $orderedProductTax
     * @return void
     */
    public function restored(OrderedProductTax $orderedProductTax)
    {
        //
    }

    /**
     * Handle the OrderedProductTax "force deleted" event.
     *
     * @param  \App\Models\OrderedProductTax  $orderedProductTax
     * @return void
     */
    public function forceDeleted(OrderedProductTax $orderedProductTax)
    {
        //
    }
}
