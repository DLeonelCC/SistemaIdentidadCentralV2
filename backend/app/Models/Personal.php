<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personals';

    protected $fillable = [
        'codigo',
        'codigo_air',
        'fec_ini',
        'fec_fin',
        'tipo_contrato',

        'cond_g_oc',
        'nivel_remunerativo',
        'tiempo_entidad',
        'quinquenio',
        'rep_provincia',
        'categoria_ocupacional',

        'sis_pen',
        'tipo_afp',
        'cuspp',
        'tip_com_seg',

        'discapacidad',
        'sindicalizado',

        'observacion',
        'condicion',
        
        'persona_num_doc',
        'tenant_id',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;

    public function persona()
    {
        return $this->belongsTo("App\Models\Persona", "num_doc", "persona_num_doc");
    }

    public function user()
    {
        return $this->hasOne("App\Models\User", "codigo", "codigo_personal");
    }

    public function getPersonaAttribute()
    {
        $persona = Persona::where('num_doc', $this->persona_num_doc)->first();
        if ($persona) {
            return $persona->append('tenant');
        }
        return null;
    }

    public function getUserAttribute()
    {
        return User::where('persona_num_doc', $this->num_doc)
            ->where('tipo', 'NORMAL')
            ->first();
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }
}
