<?php

namespace App\Observers;

use App\Models\Discount;
use Illuminate\Support\Facades\Auth;


class DiscountObserver
{
    /**
     * Handle the Discount "created" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function creating(Discount $discount)
    {
        $discount->created_by = Auth::user()->name;

    }

    /**
     * Handle the Discount "updated" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function updating(Discount $discount)
    {
        $discount->updated_by = Auth::user()->name;

    }

    /**
     * Handle the Discount "deleted" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function deleted(Discount $discount)
    {
        //
    }

    /**
     * Handle the Discount "restored" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function restored(Discount $discount)
    {
        //
    }

    /**
     * Handle the Discount "force deleted" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function forceDeleted(Discount $discount)
    {
        //
    }
}
