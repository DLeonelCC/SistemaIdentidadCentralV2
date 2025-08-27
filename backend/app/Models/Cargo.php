<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';

    protected $fillable = [
        'codigo',
        'nombre',
        'ordinal',
        'tenant_id',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;

    public function personals()
    {
        return $this->hasMany("App\Models\Personal");
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }

    protected static function booted()
    {
        static::creating(function ($oficina) {
            // Obtener el último número usado
            $lastCodigo = self::where('codigo', 'like', 'CA-%')
                ->orderByDesc('codigo')
                ->value('codigo'); // Ej: 'CA-0023'

            // Extraer el número y sumarle 1
            $nextNumber = $lastCodigo
                ? ((int) substr($lastCodigo, 3)) + 1
                : 1;

            // Formatear el código
            $oficina->codigo = 'CA-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }
}
