<?php

namespace App\Observers;

use App\Models\SalesReturn;
use Illuminate\Support\Facades\Auth;

class SalesReturnObserver
{
    /**
     * Handle the SalesReturn "created" event.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return void
     */
    public function creating(SalesReturn $salesReturn)
    {
        $salesReturn->created_by = Auth::user()->name;
    }

    /**
     * Handle the SalesReturn "updating" event.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return void
     */
    public function updating(SalesReturn $salesReturn)
    {
        $salesReturn->updated_by = Auth::user()->name;
    }

    /**
     * Handle the SalesReturn "deleted" event.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return void
     */
    public function deleted(SalesReturn $salesReturn)
    {
        //
    }

    /**
     * Handle the SalesReturn "restored" event.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return void
     */
    public function restored(SalesReturn $salesReturn)
    {
        //
    }

    /**
     * Handle the SalesReturn "force deleted" event.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return void
     */
    public function forceDeleted(SalesReturn $salesReturn)
    {
        //
    }
}
