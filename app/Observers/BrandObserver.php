<?php

namespace App\Observers;

use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function creating(Brand $brand)
    {
        if(session()->has('subscriberId')){
            $brand->created_by = Session('subscriberId');
        }
        else{
            $brand->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Brand "updated" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function updating(Brand $brand)
    {
        if(session()->has('subscriberId')){
            $brand->updated_by = Session('subscriberId');
        }
        else{
            $brand->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function deleted(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "restored" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function restored(Brand $brand)
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function forceDeleted(Brand $brand)
    {
        //
    }
}
