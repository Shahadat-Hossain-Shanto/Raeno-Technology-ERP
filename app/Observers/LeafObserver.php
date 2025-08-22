<?php

namespace App\Observers;

use App\Models\Leaf;
use Illuminate\Support\Facades\Auth;

class LeafObserver
{
    /**
     * Handle the Leaf "created" event.
     *
     * @param  \App\Models\Leaf  $leaf
     * @return void
     */
    public function creating(Leaf $leaf)
    {
        $leaf->created_by = Auth::user()->name;
    }

    /**
     * Handle the Leaf "updated" event.
     *
     * @param  \App\Models\Leaf  $leaf
     * @return void
     */
    public function updating(Leaf $leaf)
    {
        $leaf->updated_by = Auth::user()->name;
    }

    /**
     * Handle the Leaf "deleted" event.
     *
     * @param  \App\Models\Leaf  $leaf
     * @return void
     */
    public function deleted(Leaf $leaf)
    {
        //
    }

    /**
     * Handle the Leaf "restored" event.
     *
     * @param  \App\Models\Leaf  $leaf
     * @return void
     */
    public function restored(Leaf $leaf)
    {
        //
    }

    /**
     * Handle the Leaf "force deleted" event.
     *
     * @param  \App\Models\Leaf  $leaf
     * @return void
     */
    public function forceDeleted(Leaf $leaf)
    {
        //
    }
}
