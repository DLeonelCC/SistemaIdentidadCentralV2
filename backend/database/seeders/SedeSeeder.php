<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::updateOrCreate(['nombre' => 'SEDE CENTRAL'], [
            'nombre' => 'SEDE CENTRAL',
            'direccion' => 'Jr. Destua N° 356',
        ]);

        Sede::updateOrCreate(['nombre' => 'SUB SEDE MOQUEGUA'], [
            'nombre' => 'SUB SEDE MOQUEGUA',
            'direccion' => '',
        ]);

        Sede::updateOrCreate(['nombre' => 'SUB SEDE CARABAYA'], [
            'nombre' => 'SUB SEDE CARABAYA',
            'direccion' => '',
        ]);

        Sede::updateOrCreate(['nombre' => 'SEDE DIRECCIÓN DE TRABAJO'], [
            'nombre' => 'SEDE DIRECCIÓN DE TRABAJO',
            'direccion' => '',
        ]);

        Sede::updateOrCreate(['nombre' => 'SEDE ARCHIVO REGIONAL'], [
            'nombre' => 'SEDE ARCHIVO REGIONAL',
            'direccion' => '',
        ]);

        Sede::updateOrCreate(['nombre' => 'SEDE DIRCETUR'], [
            'nombre' => 'SEDE DIRCETUR',
            'direccion' => '',
        ]);
    }
}
