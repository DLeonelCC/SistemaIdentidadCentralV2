<?php

namespace App\Models;

use App\Observers\RoleObserver;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function getSistemaAttribute()
    {
        return Sistema::where('id', $this->sistema_id)->first();
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }

    protected static function booted(): void
    {
        Role::observe(RoleObserver::class);
    }
}
