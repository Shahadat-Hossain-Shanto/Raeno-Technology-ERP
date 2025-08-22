<?php

namespace App\Observers;

use App\Models\DuePayment;
use Illuminate\Support\Facades\Auth;

class DuePaymentObserver
{
    /**
     * Handle the DuePayment "created" event.
     *
     * @param  \App\Models\DuePayment  $duePayment
     * @return void
     */
    public function creating(DuePayment $duePayment)
    {
        if(session()->has('subscriberId')){
            $duePayment->created_by = Session('subscriberId');
        }
        else{
            $duePayment->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the DuePayment "updated" event.
     *
     * @param  \App\Models\DuePayment  $duePayment
     * @return void
     */
    public function updating(DuePayment $duePayment)
    {
        if(session()->has('subscriberId')){
            $duePayment->updated_by = Session('subscriberId');
        }
        else{
            $duePayment->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the DuePayment "deleted" event.
     *
     * @param  \App\Models\DuePayment  $duePayment
     * @return void
     */
    public function deleted(DuePayment $duePayment)
    {
        //
    }

    /**
     * Handle the DuePayment "restored" event.
     *
     * @param  \App\Models\DuePayment  $duePayment
     * @return void
     */
    public function restored(DuePayment $duePayment)
    {
        //
    }

    /**
     * Handle the DuePayment "force deleted" event.
     *
     * @param  \App\Models\DuePayment  $duePayment
     * @return void
     */
    public function forceDeleted(DuePayment $duePayment)
    {
        //
    }
}
