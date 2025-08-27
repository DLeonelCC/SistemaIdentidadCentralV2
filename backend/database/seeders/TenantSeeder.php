<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::updateOrCreate(
            ['siglas' => 'GRP', 'nombre' => 'GOBIERNO REGIONAL PUNO - CENTRAL']
        );

        Tenant::updateOrCreate(
            ['siglas' => 'SS', 'nombre' => 'SUB SEDE']
        );
    }
}
