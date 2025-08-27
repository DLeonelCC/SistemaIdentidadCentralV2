<?php

namespace App\Observers;

use app\Models\Role;

class RoleObserver
{
    /**
     * Handle the role "created" event.
     */
    public function created(Role $role): void
    {
        //
    }

    /**
     * Handle the role "updated" event.
     */
    public function updated(Role $role): void
    {
        //
    }

    /**
     * Handle the role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        //
    }

    /**
     * Handle the role "restored" event.
     */
    public function restored(Role $role): void
    {
        //
    }

    /**
     * Handle the role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        //
    }
}
