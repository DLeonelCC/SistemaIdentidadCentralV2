<?php

namespace App\Observers;

use App\Models\Empresa;
use App\Models\Oficina;

class OficinaObserver
{
    /**
     * Handle the Oficina "created" event.
     */
    public function created(Oficina $oficina): void
    {
        Empresa::find(1)->increment('tot_oficinas', 1);
    }

    /**
     * Handle the Oficina "updated" event.
     */
    public function updated(Oficina $oficina): void
    {
        //
    }

    /**
     * Handle the Oficina "deleted" event.
     */
    public function deleted(Oficina $oficina): void
    {
        Empresa::find(1)->decrement('tot_oficinas', 1);
    }

    /**
     * Handle the Oficina "restored" event.
     */
    public function restored(Oficina $oficina): void
    {
        //
    }

    /**
     * Handle the Oficina "force deleted" event.
     */
    public function forceDeleted(Oficina $oficina): void
    {
        //
    }
}
