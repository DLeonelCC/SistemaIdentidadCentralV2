<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,
            
            OauthClientSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

            UbigeoSeeder::class,
            PersonaSeeder::class,
            CargoSeeder::class,
            SedeSeeder::class,
            ProyectoSeeder::class,
            OficinaSeeder::class,
            PersonalSeeder::class,
            TenantSeeder::class,
        ]);
    }
}
