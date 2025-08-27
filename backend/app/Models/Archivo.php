<?php

namespace App\Models;

use App\Models\Persona;
use App\Observers\ArchivoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Archivo extends Model
{
    use HasFactory;

    protected $table = 'archivos';

    protected $fillable = [
        'tipo',
        'tipo_doc',
        'num_doc',
        'fecha',
        'descripcion',
        'url_archivo',
        'contrato_id',
        'tenant_id',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;

    public function saveArchivo(UploadedFile $archivo)
    {
        tap($this->url_archivo, function ($previous) use ($archivo) {
            $this->forceFill([
                'url_archivo' => $archivo->storePublicly(
                    'archivos',
                    ['disk' => 'public']
                ),
            ])->save();

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });
    }

    public function deleteArchivo()
    {
        if (is_null($this->url_archivo)) {
            return;
        }

        Storage::disk('public')->delete($this->url_archivo);

        $this->forceFill([
            'url_archivo' => null,
        ])->save();
    }

    protected static function booted(): void
    {
        Archivo::observe(ArchivoObserver::class);
    }

    public function getTenantAttribute()
    {
        return Tenant::where('id', $this->tenant_id)->first();
    }
}
