<?php

namespace App\Observers;

use App\Models\HoldSale;
use Illuminate\Support\Facades\Auth;


class HoldSaleObserver
{
    /**
     * Handle the HoldSale "created" event.
     *
     * @param  \App\Models\HoldSale  $holdSale
     * @return void
     */
    public function creating(HoldSale $holdSale)
    {
        $holdSale->created_by = Auth::user()->name;
    }

    /**
     * Handle the HoldSale "updated" event.
     *
     * @param  \App\Models\HoldSale  $holdSale
     * @return void
     */
    public function updating(HoldSale $holdSale)
    {
        $holdSale->updated_by = Auth::user()->name;
    }

    /**
     * Handle the HoldSale "deleted" event.
     *
     * @param  \App\Models\HoldSale  $holdSale
     * @return void
     */
    public function deleted(HoldSale $holdSale)
    {
        //
    }

    /**
     * Handle the HoldSale "restored" event.
     *
     * @param  \App\Models\HoldSale  $holdSale
     * @return void
     */
    public function restored(HoldSale $holdSale)
    {
        //
    }

    /**
     * Handle the HoldSale "force deleted" event.
     *
     * @param  \App\Models\HoldSale  $holdSale
     * @return void
     */
    public function forceDeleted(HoldSale $holdSale)
    {
        //
    }
}
