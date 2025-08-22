<?php

namespace App\Observers;

use App\Models\StoreVat;
use Illuminate\Support\Facades\Auth;

class StoreVatObserver
{
    /**
     * Handle the StoreVat "created" event.
     *
     * @param  \App\Models\StoreVat  $storeVat
     * @return void
     */
    public function creating(StoreVat $storeVat)
    {
        $storeVat->created_by = Auth::user()->name;
    }

    /**
     * Handle the StoreVat "updated" event.
     *
     * @param  \App\Models\StoreVat  $storeVat
     * @return void
     */
    public function updating(StoreVat $storeVat)
    {
        $storeVat->created_by = Auth::user()->name;
    }

    /**
     * Handle the StoreVat "deleted" event.
     *
     * @param  \App\Models\StoreVat  $storeVat
     * @return void
     */
    public function deleted(StoreVat $storeVat)
    {
        //
    }

    /**
     * Handle the StoreVat "restored" event.
     *
     * @param  \App\Models\StoreVat  $storeVat
     * @return void
     */
    public function restored(StoreVat $storeVat)
    {
        //
    }

    /**
     * Handle the StoreVat "force deleted" event.
     *
     * @param  \App\Models\StoreVat  $storeVat
     * @return void
     */
    public function forceDeleted(StoreVat $storeVat)
    {
        //
    }
}
