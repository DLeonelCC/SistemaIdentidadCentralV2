<?php

namespace App\Observers;

use App\Models\Empresa;
use App\Models\Sede;

class SedeObserver
{
    /**
     * Handle the Sede "created" event.
     */
    public function created(Sede $sede): void
    {
        Empresa::find(1)->increment('tot_sedes', 1);
    }

    /**
     * Handle the Sede "updated" event.
     */
    public function updated(Sede $sede): void
    {
        //
    }

    /**
     * Handle the Sede "deleted" event.
     */
    public function deleted(Sede $sede): void
    {
        Empresa::find(1)->decrement('tot_sedes', 1);
    }

    /**
     * Handle the Sede "restored" event.
     */
    public function restored(Sede $sede): void
    {
        //
    }

    /**
     * Handle the Sede "force deleted" event.
     */
    public function forceDeleted(Sede $sede): void
    {
        //
    }
}
