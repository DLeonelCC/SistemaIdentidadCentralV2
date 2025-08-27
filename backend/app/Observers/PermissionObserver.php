<?php

namespace App\Observers;

use app\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionObserver
{
     /**
     * Handle the permission "created" event.
     */
    public function created(Permission $permission): void
    {
        //
    }

    /**
     * Handle the permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        //
    }

    /**
     * Handle the permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {

    }

    /**
     * Handle the permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        //
    }

    /**
     * Handle the permission "force deleted" event.
     */
    public function forceDeleted(Permission $permission): void
    {
        //
    }
}
