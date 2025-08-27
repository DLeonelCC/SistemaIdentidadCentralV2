<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAllCommand extends Command
{
    protected $signature = 'migrate:all';
    protected $description = 'Ejecuta todas las migraciones, incluyendo core_central, Passport y Spatie, y crea las bases de datos si no existen';

    public function handle()
    {
        $this->info('🔄 Verificando y creando bases de datos si no existen...');

        $this->createDatabaseIfNotExists(config('database.connections.mysql.database'));
        $this->createDatabaseIfNotExists(config('database.connections.core_central.database'));

        $this->info('🚀 Ejecutando migraciones base...');
        $this->call('migrate');

        $this->info('🚀 Ejecutando migraciones core_central...');
        $this->call('migrate', [
            '--path' => 'database/migrations/core_central',
            '--database' => 'core_central'
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

        $this->info('🔑 Instalando claves de Passport...');
        $this->call('passport:install', ['--force' => true]);

        $this->info('✅ Todas las migraciones se ejecutaron correctamente.');
    }

    protected function createDatabaseIfNotExists($dbName)
    {
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        $default = DB::connection()->getConfig();

        $connection = new \PDO(
            "mysql:host={$default['host']};port={$default['port']}",
            $default['username'],
            $default['password']
        );

        $connection->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE $collation");
        $this->info("📦 Base de datos '$dbName' verificada/creada");
    }
}
