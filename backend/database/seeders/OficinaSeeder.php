<?php

namespace Database\Seeders;

use App\Models\Oficina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OficinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Oficina::updateOrCreate(['nombre' => 'PLANEAMIENTO Y PRESUPUESTO'], [
            'nombre' => 'PLANEAMIENTO Y PRESUPUESTO'
        ]);

        Oficina::updateOrCreate(['nombre' => 'GESTION ADMINISTRATIVA'], [
            'nombre' => 'GESTION ADMINISTRATIVA'
        ]);

        Oficina::updateOrCreate(['nombre' => 'ASESORAMIENTO TECNICO Y JURIDICO'], [
            'nombre' => 'ASESORAMIENTO TECNICO Y JURIDICO'
        ]);

        Oficina::updateOrCreate(['nombre' => 'ACCIONES DE CONTROL Y AUDITORIA'], [
            'nombre' => 'ACCIONES DE CONTROL Y AUDITORIA'
        ]);

        Oficina::updateOrCreate(['nombre' => 'ASESORAMIENTO Y GESTION DEL MEDIO AMBIENTE'], [
            'nombre' => 'ASESORAMIENTO Y GESTION DEL MEDIO AMBIENTE'
        ]);

        Oficina::updateOrCreate(['nombre' => 'GERENCIA REGIONAL'], [
            'nombre' => 'GERENCIA REGIONAL'
        ]);

        Oficina::updateOrCreate(['nombre' => 'DESARROLLO SOCIAL REGIONAL'], [
            'nombre' => 'DESARROLLO SOCIAL REGIONAL'
        ]);

        Oficina::updateOrCreate(['nombre' => 'GESTION DE LA INFRAESTRUCTURA'], [
            'nombre' => 'GESTION DE LA INFRAESTRUCTURA'
        ]);

        Oficina::updateOrCreate(['nombre' => 'PROCURADURIA'], [
            'nombre' => 'PROCURADURIA'
        ]);
    }
}
