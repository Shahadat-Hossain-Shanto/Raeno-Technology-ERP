<?php

namespace App\Observers;

use App\Models\ProductUnit;
use Illuminate\Support\Facades\Auth;

class ProductUnitObserver
{
    /**
     * Handle the ProductUnit "created" event.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return void
     */
    public function creating(ProductUnit $productUnit)
    {
        // $productUnit->created_by = Auth::user()->name;
        if (session()->has('subscriberId')) {
            $productUnit->created_by = Session('subscriberId');
        } else {
            $productUnit->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the ProductUnit "updated" event.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return void
     */
    public function updating(ProductUnit $productUnit)
    {
        if (session()->has('subscriberId')) {
            $productUnit->updated_by = Session('subscriberId');
        } else {
            $productUnit->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the ProductUnit "deleted" event.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return void
     */
    public function deleted(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Handle the ProductUnit "restored" event.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return void
     */
    public function restored(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Handle the ProductUnit "force deleted" event.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return void
     */
    public function forceDeleted(ProductUnit $productUnit)
    {
        //
    }
}
