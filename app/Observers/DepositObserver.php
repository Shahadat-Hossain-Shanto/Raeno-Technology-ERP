<?php

namespace App\Observers;

use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;

class DepositObserver
{
    /**
     * Handle the Deposit "created" event.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return void
     */
    public function creating(Deposit $deposit)
    {
        if(session()->has('subscriberId')){
            $deposit->created_by = Session('subscriberId');
        }
        else{
            $deposit->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Deposit "updated" event.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return void
     */
    public function updating(Deposit $deposit)
    {
        if(session()->has('subscriberId')){
            $deposit->updated_by = Session('subscriberId');
        }
        else{
            $deposit->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Deposit "deleted" event.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return void
     */
    public function deleted(Deposit $deposit)
    {
        //
    }

    /**
     * Handle the Deposit "restored" event.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return void
     */
    public function restored(Deposit $deposit)
    {
        //
    }

    /**
     * Handle the Deposit "force deleted" event.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return void
     */
    public function forceDeleted(Deposit $deposit)
    {
        //
    }
}
