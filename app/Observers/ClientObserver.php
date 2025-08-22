<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;


class ClientObserver
{
    /**
     * Handle the Client "created" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function creating(Client $client)
    {
        if(session()->has('subscriberId')){
            $client->created_by = Session('subscriberId');
        }
        else{
            $client->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the Client "updated" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function updating(Client $client)
    {
        if(session()->has('subscriberId')){
            $client->updated_by = Session('subscriberId');
        }
        else{
            $client->updated_by = Auth::user()->subscriber_id;
        }

    }

    /**
     * Handle the Client "deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function deleted(Client $client)
    {
        //
    }

    /**
     * Handle the Client "restored" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
