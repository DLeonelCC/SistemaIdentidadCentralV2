<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::updateOrCreate(['ruc' => '20146247084'], [
            'ruc' => '20146247084',
            'razon_social' => 'MUNICIPALIDAD PROVINCIAL DE PUNO',
        ]);
    }
}
