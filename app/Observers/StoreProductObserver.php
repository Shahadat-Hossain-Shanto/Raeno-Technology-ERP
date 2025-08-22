<?php

namespace App\Observers;

use App\Models\StoreProduct;
use Illuminate\Support\Facades\Auth;


class StoreProductObserver
{
    /**
     * Handle the StoreProduct "created" event.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return void
     */
    public function creating(StoreProduct $storeProduct)
    {
        $storeProduct->created_by = Auth::user()->name;

    }

    /**
     * Handle the StoreProduct "updated" event.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return void
     */
    public function updating(StoreProduct $storeProduct)
    {
        $storeProduct->updated_by = Auth::user()->name;
    }

    /**
     * Handle the StoreProduct "deleted" event.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return void
     */
    public function deleted(StoreProduct $storeProduct)
    {
        //
    }

    /**
     * Handle the StoreProduct "restored" event.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return void
     */
    public function restored(StoreProduct $storeProduct)
    {
        //
    }

    /**
     * Handle the StoreProduct "force deleted" event.
     *
     * @param  \App\Models\StoreProduct  $storeProduct
     * @return void
     */
    public function forceDeleted(StoreProduct $storeProduct)
    {
        //
    }
}
