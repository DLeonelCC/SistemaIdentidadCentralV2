<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{

    protected $guard_name = 'api';

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo',
        'name',
        'email',
        'password',
        'photo',
        'estado',
        'cargo_id',
        'oficina_id',
        'persona_num_doc',
        'tenant_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function cargas()
    {
        return $this->hasMany("App\Models\Carga");
    }

    public function gastos()
    {
        return $this->belongsToMany("App\Models\Gasto", "user_gasto");
    }

    public function proyectos()
    {
        return $this->belongsToMany("App\Models\Proyecto", "user_proyecto");
    }

    public function getPersonaAttribute()
    {
        $persona = Persona::where('num_doc', $this->persona_num_doc)->first();
        if ($persona) {
            return $persona->append('tenant');
        }
        return null;
    }

    public function getPersonalAttribute()
    {
        $personal = Personal::where('persona_num_doc', $this->persona_num_doc)->first();
        if ($personal) {
            $personal->append('meta');
        }
        return $personal;
    }

    public function getCargoAttribute()
    {
        return Cargo::where('id', $this->cargo_id)->first();
    }

    public function getOficinaAttribute()
    {
        return Oficina::where('id', $this->oficina_id)->first();
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }

    public function updatePhoto(UploadedFile $photo)
    {
        tap($this->photo, function ($previous) use ($photo) {
            $this->forceFill([
                'photo' => $photo->storePublicly(
                    'profile-photos',
                    ['disk' => 'public']
                ),
            ])->save();

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });
    }

    public function deletePhoto()
    {
        if (is_null($this->photo)) {
            return;
        }

        Storage::disk('public')->delete($this->photo);

        $this->forceFill([
            'photo' => null,
        ])->save();
    }

    public function getRolNamesCoreCentral()
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where(function ($q) {
                $q->where('model_type', '=', 'App\\Models\\User')
                    ->orWhere('model_type', '=', 'App\\Models\\core_central\\User');
            })
            ->where('model_id', '=', $this->id)
            ->pluck('roles.name');
    }

    public function getPermissionNamesCoreCentral()
    {
        $roleIds = DB::table('model_has_roles')
            ->where(function ($q) {
                $q->where('model_type', '=', 'App\\Models\\User')
                    ->orWhere('model_type', '=', 'App\\Models\\core_central\\User');
            })
            ->where('model_id', '=', $this->id)
            ->pluck('role_id');

        if ($roleIds->isEmpty()) {
            return collect();
        }

        return DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->whereIn('role_has_permissions.role_id', $roleIds)
            ->pluck('permissions.name');
    }
}
