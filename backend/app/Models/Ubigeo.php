<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    use HasFactory;
    
    protected $table = 'ubigeos';
    protected $fillable = [
        'codigo',
        'tipo',
        'cod_dep',
        'cod_prov',
        'cod_dist',
        'nombre',
    ];

    protected $primaryKey = 'codigo';
    protected $guarded = 'id';
    public $timestamps = true;

    public function personas_nacimiento()
    {
        return $this->hasMany("App\Models\Ubigeo", "ubigeo_nacimiento", "codigo",);
    }

    public function personas_actual()
    {
        return $this->hasMany("App\Models\Ubigeo", "ubigeo_actual", "codigo");
    }
}
