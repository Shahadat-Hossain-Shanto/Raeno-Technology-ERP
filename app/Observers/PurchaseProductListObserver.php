<?php

namespace App\Observers;

use App\Models\PurchaseProductList;
use Illuminate\Support\Facades\Auth;


class PurchaseProductListObserver
{
    /**
     * Handle the PurchaseProductList "created" event.
     *
     * @param  \App\Models\PurchaseProductList  $purchaseProductList
     * @return void
     */
    public function creating(PurchaseProductList $purchaseProductList)
    {
        // $purchaseProductList->created_by = Auth::user()->org_name;
        $purchaseProductList->created_by = Auth::user()->name;

    }

    /**
     * Handle the PurchaseProductList "updated" event.
     *
     * @param  \App\Models\PurchaseProductList  $purchaseProductList
     * @return void
     */
    public function updating(PurchaseProductList $purchaseProductList)
    {
        // $purchaseProductList->updated_by = Auth::user()->org_name;
        $purchaseProductList->updated_by = Auth::user()->name;

    }

    /**
     * Handle the PurchaseProductList "deleted" event.
     *
     * @param  \App\Models\PurchaseProductList  $purchaseProductList
     * @return void
     */
    public function deleted(PurchaseProductList $purchaseProductList)
    {
        //
    }

    /**
     * Handle the PurchaseProductList "restored" event.
     *
     * @param  \App\Models\PurchaseProductList  $purchaseProductList
     * @return void
     */
    public function restored(PurchaseProductList $purchaseProductList)
    {
        //
    }

    /**
     * Handle the PurchaseProductList "force deleted" event.
     *
     * @param  \App\Models\PurchaseProductList  $purchaseProductList
     * @return void
     */
    public function forceDeleted(PurchaseProductList $purchaseProductList)
    {
        //
    }
}
