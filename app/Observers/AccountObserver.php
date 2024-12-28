<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Log;
class AccountObserver 
{
    /**
     * Handle the Account "created" event.
     */
    public function created(Account $account): void
    {
        if (!app()->runningInConsole()) { // Stop Observer  During DB Seeding
        Log::create([
            'module_name' => 'Account',
            'action' => 'create',
            'badge' => 'success',
            'affected_record_id' => $account->id,
            'updated_data' => json_encode($account),
           'by_user_id' => auth()->id() ?? $account->id, 
        ]);
    }
    }

    /**
     * Handle the Account "updated" event.
     */
    public function updated(Account $account): void
    {
        // Get the original data before the update
        $originalData = $account->getOriginal();

        Log::create([
            'module_name' => 'Account',
            'action' => 'update',
            'badge' => 'primary',
            'affected_record_id' => $account->id,
            'original_data' => json_encode($originalData),
            'updated_data' => json_encode($account),
            'by_user_id'=> auth()->id(),
        ]);
    }

    /**
     * Handle the Account "deleted" event.
     */
    public function deleted(Account $account): void
    {
        Log::create([
            'module_name' => 'Account',
            'action' => 'delete',
            'badge' => 'danger',
            'affected_record_id' => $account->id,
            'original_data' => json_encode($account),
            'by_user_id'=> auth()->id(),
        ]);
    }

    /**
     * Handle the Account "restored" event.
     */
    public function restored(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "force deleted" event.
     */
    public function forceDeleted(Account $account): void
    {
        //
    }
}
