<?php

namespace App\Observers;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryObserver
{
    /**
     * Handle the ExpenseCategory "created" event.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return void
     */
    public function creating(ExpenseCategory $expenseCategory)
    {
        // $expenseCategory->created_by = Auth::user()->name;

        if(session()->has('subscriberId')){
            $expenseCategory->created_by = Session('subscriberId');
        }
        else{
            $expenseCategory->created_by = Auth::user()->subscriber_id;
        }
    }

    /**
     * Handle the ExpenseCategory "updated" event.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return void
     */
    public function updating(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->updated_by = Auth::user()->name;
    }

    /**
     * Handle the ExpenseCategory "deleted" event.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return void
     */
    public function deleted(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Handle the ExpenseCategory "restored" event.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return void
     */
    public function restored(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Handle the ExpenseCategory "force deleted" event.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return void
     */
    public function forceDeleted(ExpenseCategory $expenseCategory)
    {
        //
    }
}
