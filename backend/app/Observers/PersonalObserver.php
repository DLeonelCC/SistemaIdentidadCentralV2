<?php

namespace App\Observers;

use App\Models\Empresa;
use App\Models\Personal;

class PersonalObserver
{
    /**
     * Handle the Personal "created" event.
     */
    public function created(Personal $personal): void
    {
        Empresa::find(1)->increment('tot_personals', 1);
    }

    /**
     * Handle the Personal "updated" event.
     */
    public function updated(Personal $personal): void
    {
        //
    }

    /**
     * Handle the Personal "deleted" event.
     */
    public function deleted(Personal $personal): void
    {
        Empresa::find(1)->decrement('tot_personals', 1);
    }

    /**
     * Handle the Personal "restored" event.
     */
    public function restored(Personal $personal): void
    {
        //
    }

    /**
     * Handle the Personal "force deleted" event.
     */
    public function forceDeleted(Personal $personal): void
    {
        //
    }
}
