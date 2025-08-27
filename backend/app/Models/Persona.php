<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    
    protected $table = 'personas';

    protected $fillable = [
        'tipo_doc',
        'num_doc',
        'nombre_completo',
        'nombres',
        'ap_paterno',
        'ap_materno',
        'email',
        'direccion',
        'celular',
        'fec_nacimiento',
        'sexo',
        'num_cuenta',
        'nacionalidad',
        'estado_civil',
        'ruc',
        'pais',
        'datos_completos',
        'ubigeo_actual',
        'ubigeo_nacimiento',
        'tenant_id',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;

    public function personal()
    {
        return $this->hasOne("App\Models\Personal", "num_doc", "personal_num_doc");
    }

    public static function validarNumDoc(string $numDoc)
    {
        return static::where('num_doc', $numDoc)
            ->count() > 0;
    }

    public function getUserAttribute()
    {
        return User::where('persona_num_doc', $this->num_doc)
            ->where('tipo', 'NORMAL')
            ->first();
    }

    public function validarDatosCompletos()
    {
        $datos_completos = 1;
        if ($this->tipo_doc == null) {
            $datos_completos = 0;
        }
        if ($this->num_doc == null) {
            $datos_completos = 0;
        }
        if ($this->nombre_completo == null) {
            $datos_completos = 0;
        }
        if ($this->nombres == null) {
            $datos_completos = 0;
        }
        if ($this->ap_paterno == null) {
            $datos_completos = 0;
        }
        if ($this->ap_materno == null) {
            $datos_completos = 0;
        }
        if ($this->email == null) {
            $datos_completos = 0;
        }
        if ($this->celular == null) {
            $datos_completos = 0;
        }
        if ($this->sexo == null) {
            $datos_completos = 0;
        }
        // if ($this->ruc == null) {
        //     $datos_completos = 0;
        // }
        if ($this->fec_nacimiento == null) {
            $datos_completos = 0;
        }
        if ($this->nacionalidad == null) {
            $datos_completos = 0;
        }
        if ($this->estado_civil == null) {
            $datos_completos = 0;
        }
        if ($this->pais == null) {
            $datos_completos = 0;
        }
        if ($this->num_cuenta == null) {
            $datos_completos = 0;
        }
        $personal = Persona::find($this->id);
        $personal->update([
            'datos_completos' => $datos_completos
        ]);
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }
}
