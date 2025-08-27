<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Empresa extends Model
{
    use HasFactory;
    
    protected $table = 'empresas';

    protected $fillable = [
        'ruc',
        'razon_social',
        'email',
        'logo',
        'tot_sedes',
        'tot_oficinas',
        'tot_proyectos',
        'tot_users',
        'tot_personals',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;

    /**
     * Update the empresa logo
     *
     * @param  \Illuminate\Http\UploadedFile  $logo
     * @return void
     */
    public function updateLogo(UploadedFile $logo)
    {
        tap($this->logo, function ($previous) use ($logo) {
            $this->forceFill([
                'logo' => $logo->storePublicly(
                    'empresa-logos',
                    ['disk' => 'public']
                ),
            ])->save();

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });
    }

    /**
     * Delete the empresa logo
     *
     * @return void
     */
    public function deleteLogo()
    {
        if (is_null($this->logo)) {
            return;
        }

        Storage::disk('public')->delete($this->logo);

        $this->forceFill([
            'logo' => null,
        ])->save();
    }
}
