<?php

namespace App\Observers;

use App\Models\Archivo;

class ArchivoObserver
{
    /**
     * Handle the Archivo "created" event.
     */
    public function created(Archivo $archivo): void
    {
        //
    }

    /**
     * Handle the Archivo "updated" event.
     */
    public function updated(Archivo $archivo): void
    {
        //
    }

    /**
     * Handle the Archivo "deleted" event.
     */
    public function deleted(Archivo $archivo): void
    {
        $archivo->deleteArchivo();
    }

    /**
     * Handle the Archivo "restored" event.
     */
    public function restored(Archivo $archivo): void
    {
        //
    }

    /**
     * Handle the Archivo "force deleted" event.
     */
    public function forceDeleted(Archivo $archivo): void
    {
        //
    }
}
