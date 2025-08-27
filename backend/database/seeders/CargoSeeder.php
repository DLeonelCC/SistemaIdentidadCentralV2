<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cargo::updateOrCreate(['nombre' => 'SUPERVISOR I'], [
            'nombre' => 'SUPERVISOR I',
        ],);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO I'], [
            'nombre' => 'ABOGADO I'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ASISTENTE DE COMUNICACIONES E IMAGEN INSTITUCIONAL'], [
            'nombre' => 'ASISTENTE DE COMUNICACIONES E IMAGEN INSTITUCIONAL'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ANALISTA DE MODERNIZACION'], [
            'nombre' => 'ANALISTA DE MODERNIZACION'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ASISTENTE ADMINISTRATIVO I'], [
            'nombre' => 'ASISTENTE ADMINISTRATIVO I'
        ]);

        Cargo::updateOrCreate(['nombre' => 'OPERADOR TECNICO'], [
            'nombre' => 'OPERADOR TECNICO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'TECNICO ADMINISTRATIVO'], [
            'nombre' => 'TECNICO ADMINISTRATIVO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO'], [
            'nombre' => 'ABOGADO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ESPECIALISTA EN PROCESOS'], [
            'nombre' => 'ESPECIALISTA EN PROCESOS'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ESPECIALISTA LEGAL'], [
            'nombre' => 'ESPECIALISTA LEGAL'
        ]);

        Cargo::updateOrCreate(['nombre' => 'AUXILIAR DE LIMPIEZA'], [
            'nombre' => 'AUXILIAR DE LIMPIEZA'
        ]);

        Cargo::updateOrCreate(['nombre' => 'GUARDIAN'], [
            'nombre' => 'GUARDIAN'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ESPECIALISTA EN CONTRATACIONES PUBLICAS'], [
            'nombre' => 'ESPECIALISTA EN CONTRATACIONES PUBLICAS'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ESPECIALISTA ADMINISTRATIVO'], [
            'nombre' => 'ESPECIALISTA ADMINISTRATIVO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO'], [
            'nombre' => 'ABOGADO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'TECNICO EN INFORMATICA'], [
            'nombre' => 'TECNICO EN INFORMATICA'
        ]);

        Cargo::updateOrCreate(['nombre' => 'NOTIFICADOR'], [
            'nombre' => 'NOTIFICADOR'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ADMINISTRADOR'], [
            'nombre' => 'ADMINISTRADOR'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ESPECIALISTA EN GESTION AMBIENTAL'], [
            'nombre' => 'ESPECIALISTA EN GESTION AMBIENTAL'
        ]);

        Cargo::updateOrCreate(['nombre' => 'TIA SUSTITUTA'], [
            'nombre' => 'TIA SUSTITUTA'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ASISTENTE SOCIAL'], [
            'nombre' => 'ASISTENTE SOCIAL'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ALMACENERO'], [
            'nombre' => 'ALMACENERO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ANALISTA EN ORIENTACION VOCACIONAL'], [
            'nombre' => 'ANALISTA EN ORIENTACION VOCACIONAL'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO-CONCILIADOR'], [
            'nombre' => 'ABOGADO-CONCILIADOR'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO PARA PATROCINIO'], [
            'nombre' => 'ABOGADO PARA PATROCINIO'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO ESPECIALISTA DEFENSA LEGAL'], [
            'nombre' => 'ABOGADO ESPECIALISTA DEFENSA LEGAL'
        ]);

        Cargo::updateOrCreate(['nombre' => 'ABOGADO ESPECIALISTA PREVENCION'], [
            'nombre' => 'ABOGADO ESPECIALISTA PREVENCION'
        ]);
    }
}
