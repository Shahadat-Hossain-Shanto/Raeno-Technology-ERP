<?php

namespace App\Observers;

use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\Auth;

class EmployeeLeaveObserver
{
    /**
     * Handle the EmployeeLeave "created" event.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return void
     */
    public function creating(EmployeeLeave $employeeLeave)
    {
        $employeeLeave->created_by_user = Auth::user()->name;
    }

    /**
     * Handle the EmployeeLeave "updated" event.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return void
     */
    public function updating(EmployeeLeave $employeeLeave)
    {
        $employeeLeave->updated_by_user = Auth::user()->name;
    }

    /**
     * Handle the EmployeeLeave "deleted" event.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return void
     */
    public function deleted(EmployeeLeave $employeeLeave)
    {
        //
    }

    /**
     * Handle the EmployeeLeave "restored" event.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return void
     */
    public function restored(EmployeeLeave $employeeLeave)
    {
        //
    }

    /**
     * Handle the EmployeeLeave "force deleted" event.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return void
     */
    public function forceDeleted(EmployeeLeave $employeeLeave)
    {
        //
    }
}
