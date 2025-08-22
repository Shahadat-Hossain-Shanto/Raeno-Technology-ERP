<?php

namespace App\Observers;

use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;

class SubcategoryObserver
{
    /**
     * Handle the Subcategory "created" event.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return void
     */
    public function creating(Subcategory $subcategory)
    {
        if(session()->has('subscriberId')){
            $subcategory->created_by = Session('subscriberId');
        }
        else{
            $subcategory->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Subcategory "updated" event.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return void
     */
    public function updating(Subcategory $subcategory)
    {
        if(session()->has('subscriberId')){
            $subcategory->updated_by = Session('subscriberId');
        }
        else{
            $subcategory->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Subcategory "deleted" event.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return void
     */
    public function deleted(Subcategory $subcategory)
    {
        //
    }

    /**
     * Handle the Subcategory "restored" event.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return void
     */
    public function restored(Subcategory $subcategory)
    {
        //
    }

    /**
     * Handle the Subcategory "force deleted" event.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return void
     */
    public function forceDeleted(Subcategory $subcategory)
    {
        //
    }
}
