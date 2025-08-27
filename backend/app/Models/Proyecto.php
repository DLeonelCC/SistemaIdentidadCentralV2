<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'num_doc_resolucion',
        'tipo',
        'meta',
        'meta_codigo',
        'codigo',
        'cui',
        'nombre',
        'obra',

        'pliego',
        'programa_presupuestal',
        'modalidad_ejecucion',
        'fte_fto',

        'estado',
        'cant_personal',

        'escala',
        'tipo_categoria',
        'tipo_acceso',

        'charaux1',
        'charaux2',
        'charaux3',
        'charaux4',
        'charaux5',
        'charaux6',
        'charaux7',
        'charaux8',
        'charaux9',
        'charaux10',

        'oficina_id',
        'ubigeo_codigo',
        'tenant_id',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;

    public function oficina()
    {
        return $this->hasMany("App\Models\Oficina");
    }

    public function users()
    {
        return $this->belongsToMany("App\Models\User", "user_proyecto");
    }

    public function getOficinaAttribute()
    {
        return Oficina::where('id', $this->oficina_id)->first();
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }
}
