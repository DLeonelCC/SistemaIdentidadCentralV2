<?php

namespace App\Models;

use App\Observers\PermissionObserver;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function getSistemaAttribute()
    {
        return Sistema::where('id', $this->sistema_id)->first();
    }

    protected static function booted(): void
    {
        Permission::observe(PermissionObserver::class);
    }
}
