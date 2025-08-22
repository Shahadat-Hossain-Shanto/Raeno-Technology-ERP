<?php

namespace App\Observers;

use App\Models\StoreDiscount;
use Illuminate\Support\Facades\Auth;


class StoreDiscountObserver
{
    /**
     * Handle the StoreDiscount "created" event.
     *
     * @param  \App\Models\StoreDiscount  $storeDiscount
     * @return void
     */
    public function creating(StoreDiscount $storeDiscount)
    {
        $storeDiscount->created_by = Auth::user()->name;
    }

    /**
     * Handle the StoreDiscount "updated" event.
     *
     * @param  \App\Models\StoreDiscount  $storeDiscount
     * @return void
     */
    public function updating(StoreDiscount $storeDiscount)
    {
        $storeDiscount->updated_by = Auth::user()->name;

    }

    /**
     * Handle the StoreDiscount "deleted" event.
     *
     * @param  \App\Models\StoreDiscount  $storeDiscount
     * @return void
     */
    public function deleted(StoreDiscount $storeDiscount)
    {
        //
    }

    /**
     * Handle the StoreDiscount "restored" event.
     *
     * @param  \App\Models\StoreDiscount  $storeDiscount
     * @return void
     */
    public function restored(StoreDiscount $storeDiscount)
    {
        //
    }

    /**
     * Handle the StoreDiscount "force deleted" event.
     *
     * @param  \App\Models\StoreDiscount  $storeDiscount
     * @return void
     */
    public function forceDeleted(StoreDiscount $storeDiscount)
    {
        //
    }
}
