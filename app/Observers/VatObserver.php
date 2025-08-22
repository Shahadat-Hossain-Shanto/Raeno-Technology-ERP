<?php

namespace App\Observers;

use App\Models\Vat;
use Illuminate\Support\Facades\Auth;


class VatObserver
{
    /**
     * Handle the Vat "created" event.
     *
     * @param  \App\Models\Vat  $vat
     * @return void
     */
    public function creating(Vat $vat)
    {
        $vat->created_by = Auth::user()->name;

    }

    /**
     * Handle the Vat "updated" event.
     *
     * @param  \App\Models\Vat  $vat
     * @return void
     */
    public function updating(Vat $vat)
    {
        $vat->updated_by = Auth::user()->name;

    }

    /**
     * Handle the Vat "deleted" event.
     *
     * @param  \App\Models\Vat  $vat
     * @return void
     */
    public function deleted(Vat $vat)
    {
        //
    }

    /**
     * Handle the Vat "restored" event.
     *
     * @param  \App\Models\Vat  $vat
     * @return void
     */
    public function restored(Vat $vat)
    {
        //
    }

    /**
     * Handle the Vat "force deleted" event.
     *
     * @param  \App\Models\Vat  $vat
     * @return void
     */
    public function forceDeleted(Vat $vat)
    {
        //
    }
}
