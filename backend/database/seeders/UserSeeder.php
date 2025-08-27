<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_administrador = Role::findByName('Super Administrador', 'api');
        // $asignado = Role::findByName('Asignado', 'api');

        User::firstOrCreate(['email' => 'admin@domain.com'], [
            'tipo' => 'ADMINISTRATIVO',
            'name' => 'ADMIN',
            'email' => 'admin@domain.com',
            'password' => Hash::make('admin12345'),
            'estado' => 'ACTIVO',
        ])->assignRole($super_administrador);

        // User::firstOrCreate(['email' => 'asignado@domain.com'], [
        //     'tipo' => 'ADMINISTRATIVO',
        //     'name' => 'ASIGNADO',
        //     'email' => 'asignado@domain.com',
        //     'password' => Hash::make('asignado12345'),
        // ])->assignRole($asignado);
    }
}
