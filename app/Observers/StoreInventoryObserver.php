<?php

namespace App\Observers;

use App\Models\StoreInventory;
use Illuminate\Support\Facades\Auth;


class StoreInventoryObserver
{
    /**
     * Handle the StoreInventory "created" event.
     *
     * @param  \App\Models\StoreInventory  $storeInventory
     * @return void
     */
    public function creating(StoreInventory $storeInventory)
    {
        if(session()->has('subscriberId')){
            $storeInventory->created_by = Session('subscriberId');
        }
        else{
            $storeInventory->created_by = Auth::user()->subscriber_id;}

    }

    /**
     * Handle the StoreInventory "updated" event.
     *
     * @param  \App\Models\StoreInventory  $storeInventory
     * @return void
     */
    public function updating(StoreInventory $storeInventory)
    {
        if(session()->has('subscriberId')){
            $storeInventory->updated_by = Session('subscriberId');
        }
        else{
            $storeInventory->updated_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the StoreInventory "deleted" event.
     *
     * @param  \App\Models\StoreInventory  $storeInventory
     * @return void
     */
    public function deleted(StoreInventory $storeInventory)
    {
        //
    }

    /**
     * Handle the StoreInventory "restored" event.
     *
     * @param  \App\Models\StoreInventory  $storeInventory
     * @return void
     */
    public function restored(StoreInventory $storeInventory)
    {
        //
    }

    /**
     * Handle the StoreInventory "force deleted" event.
     *
     * @param  \App\Models\StoreInventory  $storeInventory
     * @return void
     */
    public function forceDeleted(StoreInventory $storeInventory)
    {
        //
    }
}
