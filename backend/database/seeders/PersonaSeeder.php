<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Persona::updateOrCreate(['num_doc' => '76187391'], [
            'tipo_doc' => 'DNI',
            'num_doc' => '76187391',
            'nombre_completo' => 'DRAKE LEONEL CHAMBILLA CHOQUECOTA',
            'nombres' => 'DRAKE LEONEL',
            'ap_paterno' => 'CHAMBILLA',
            'ap_materno' => 'CHOQUECOTA',
            'email' => 'drakeleonelc@gmail.com',
            'celular' => '914939682',
            'sexo' => 'Masculino',
            'ruc' => '10761873917',
            'fec_nacimiento' => '1999-05-27',
            'nacionalidad' => 'PERUANO',
            'estado_civil' => 'Soltero',
            'pais' => 'PERÚ',
            'num_cuenta' => '04701091804'
        ]);
    }
}
