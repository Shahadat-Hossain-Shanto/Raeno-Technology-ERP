<?php

namespace App\Observers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuObserver
{
    /**
     * Handle the Menu "created" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function creating(Menu $menu)
    {
        $menu->created_by = Auth::user()->name;
    }

    /**
     * Handle the Menu "updated" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function updating(Menu $menu)
    {
        $menu->updated_by = Auth::user()->name;
    }

    /**
     * Handle the Menu "deleted" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function deleted(Menu $menu)
    {
        //
    }

    /**
     * Handle the Menu "restored" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function restored(Menu $menu)
    {
        //
    }

    /**
     * Handle the Menu "force deleted" event.
     *
     * @param  \App\Models\Menu  $menu
     * @return void
     */
    public function forceDeleted(Menu $menu)
    {
        //
    }
}
