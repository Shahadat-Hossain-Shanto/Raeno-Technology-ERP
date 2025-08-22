<?php

namespace App\Observers;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function creating(Payment $payment)
    {
        if(session()->has('subscriberId')){
            $payment->created_by = Session('subscriberId');
        }
        else{
            $payment->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Payment "updated" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function updating(Payment $payment)
    {
        if(session()->has('subscriberId')){
            $payment->updated_by = Session('subscriberId');
        }
        else{
            $payment->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
    }
}
