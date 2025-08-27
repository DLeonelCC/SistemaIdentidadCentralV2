<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateFreshAllCommand extends Command
{
    protected $signature = 'migrate:fresh:all';
    protected $description = 'Limpia y ejecuta todas las migraciones y seeders en ambas bases (mysql y core_central), incluyendo Passport y Spatie';

    public function handle()
    {
        $this->info('⚠️ Refrescando migraciones de la base principal...');
        $this->call('migrate:fresh', ['--seed' => true]);

        $this->info('⚠️ Refrescando migraciones de core_central...');
        $this->call('migrate:fresh', [
            '--database' => 'core_central',
            '--path' => 'database/migrations/core_central',
        ]);

        $this->info('🔐 Ejecutando migraciones de Passport en core_central...');
        $this->call('migrate', [
            '--path' => 'vendor/laravel/passport/database/migrations',
            '--database' => 'core_central'
        ]);

        $this->info('🛡️ Ejecutando migraciones de Spatie en core_central...');
        $this->call('migrate', [
            '--path' => 'vendor/spatie/laravel-permission/database/migrations',
            '--database' => 'core_central'
        ]);

        $this->info('🌱 Ejecutando seeders de core_central...');
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\core_central\\CoreDatabaseSeeder',
            '--database' => 'core_central',
        ]);

        $this->info('🔑 Instalando claves de Passport...');
        $this->call('passport:install', ['--force' => true]);

        $this->info('✅ Migraciones y seeders completados para ambas bases');
    }
}
