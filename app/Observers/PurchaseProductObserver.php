<?php

namespace App\Observers;

use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\Auth;


class PurchaseProductObserver
{
    /**
     * Handle the PurchaseProduct "created" event.
     *
     * @param  \App\Models\PurchaseProduct  $purchaseProduct
     * @return void
     */
    public function creating(PurchaseProduct $purchaseProduct)
    {
        // $purchaseProduct->created_by = Auth::user()->org_name;
        $purchaseProduct->created_by = Auth::user()->name;
    }

    /**
     * Handle the PurchaseProduct "updated" event.
     *
     * @param  \App\Models\PurchaseProduct  $purchaseProduct
     * @return void
     */
    public function updating(PurchaseProduct $purchaseProduct)
    {
        // $purchaseProduct->updated_by = Auth::user()->org_name;
        $purchaseProduct->updated_by = Auth::user()->name;

    }

    /**
     * Handle the PurchaseProduct "deleted" event.
     *
     * @param  \App\Models\PurchaseProduct  $purchaseProduct
     * @return void
     */
    public function deleted(PurchaseProduct $purchaseProduct)
    {
        //
    }

    /**
     * Handle the PurchaseProduct "restored" event.
     *
     * @param  \App\Models\PurchaseProduct  $purchaseProduct
     * @return void
     */
    public function restored(PurchaseProduct $purchaseProduct)
    {
        //
    }

    /**
     * Handle the PurchaseProduct "force deleted" event.
     *
     * @param  \App\Models\PurchaseProduct  $purchaseProduct
     * @return void
     */
    public function forceDeleted(PurchaseProduct $purchaseProduct)
    {
        //
    }
}
