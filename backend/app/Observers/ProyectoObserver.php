<?php

namespace App\Observers;

use App\Models\Empresa;
use App\Models\Proyecto;

class ProyectoObserver
{
   /**
     * Handle the Oficina "created" event.
     */
    public function created(Proyecto $proyecto): void
    {
        Empresa::find(1)->increment('tot_proyectos', 1);
    }

    /**
     * Handle the Proyecto "updated" event.
     */
    public function updated(Proyecto $proyecto): void
    {
        //
    }

    /**
     * Handle the Proyecto "deleted" event.
     */
    public function deleted(Proyecto $proyecto): void
    {
        Empresa::find(1)->decrement('tot_proyectos', 1);
    }

    /**
     * Handle the Proyecto "restored" event.
     */
    public function restored(Proyecto $proyecto): void
    {
        //
    }

    /**
     * Handle the Proyecto "force deleted" event.
     */
    public function forceDeleted(Proyecto $proyecto): void
    {
        //
    }
}
