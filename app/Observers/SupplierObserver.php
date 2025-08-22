<?php

namespace App\Observers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierObserver
{
    /**
     * Handle the Supplier "created" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function creating(Supplier $supplier)
    {
        if(session()->has('subscriberId')){
            $supplier->created_by = Session('subscriberId');
        }
        else{
            $supplier->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Supplier "updated" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function updating(Supplier $supplier)
    {
        if(session()->has('subscriberId')){
            $supplier->updated_by = Session('subscriberId');
        }
        else{
            $supplier->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Supplier "deleted" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function deleted(Supplier $supplier)
    {
        //
    }

    /**
     * Handle the Supplier "restored" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function restored(Supplier $supplier)
    {
        //
    }

    /**
     * Handle the Supplier "force deleted" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function forceDeleted(Supplier $supplier)
    {
        //
    }
}
