<?php

namespace App\Observers;

use App\Models\Batch;
use Illuminate\Support\Facades\Auth;

class BatchObserver
{
    /**
     * Handle the Batch "created" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function creating(Batch $batch)
    {
        $batch->created_by = Auth::user()->name;
    }

    /**
     * Handle the Batch "updated" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function updating(Batch $batch)
    {
        $batch->updated_by = Auth::user()->name;
    }

    /**
     * Handle the Batch "deleted" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function deleted(Batch $batch)
    {
        //
    }

    /**
     * Handle the Batch "restored" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function restored(Batch $batch)
    {
        //
    }

    /**
     * Handle the Batch "force deleted" event.
     *
     * @param  \App\Models\Batch  $batch
     * @return void
     */
    public function forceDeleted(Batch $batch)
    {
        //
    }
}
