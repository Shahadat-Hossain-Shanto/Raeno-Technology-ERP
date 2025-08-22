<?php

namespace App\Observers;

use App\Models\Pos;
use Illuminate\Support\Facades\Auth;


class PosObserver
{


    /**
     * Handle the Pos "created" event.
     *
     * @param  \App\Models\Pos  $pos
     * @return void
     */
    public function creating(Pos $pos)
    {
        // $pos->created_by = Auth::user()->name;
        if (session()->has('subscriberId')) {
            $pos->created_by = Session('subscriberId');
        } else {
            $pos->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Pos "updated" event.
     *
     * @param  \App\Models\Pos  $pos
     * @return void
     */
    public function updating(Pos $pos)
    {
        $pos->updated_by = Auth::user()->subscriber_id;
    }

    /**
     * Handle the Pos "deleted" event.
     *
     * @param  \App\Models\Pos  $pos
     * @return void
     */
    public function deleted(Pos $pos)
    {
        //
    }

    /**
     * Handle the Pos "restored" event.
     *
     * @param  \App\Models\Pos  $pos
     * @return void
     */
    public function restored(Pos $pos)
    {
        //
    }

    /**
     * Handle the Pos "force deleted" event.
     *
     * @param  \App\Models\Pos  $pos
     * @return void
     */
    public function forceDeleted(Pos $pos)
    {
        //
    }
}
