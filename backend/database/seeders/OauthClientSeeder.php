<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('oauth_clients')->insert([
            'id' => 1,
            'name' => 'Laravel Personal Access Client',
            'secret' => 'n2H8y4ElDzXNFwjN76SZEQazHyithmtv2UDanm60',
            'provider' => null,
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => '2023-07-17 18:19:14',
            'updated_at' => '2023-07-17 18:19:14'
        ]);

        DB::table('oauth_clients')->insert([
            'id' => 2,
            'name' => 'Laravel Password Grant Client',
            'secret' => 'ws8czL43rBlC8vSGEsChT2IGbO1nxlsjjtAdx3xM',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => '2023-07-17 18:19:14',
            'updated_at' => '2023-07-17 18:19:14'
        ]);

        // Para sistemas externos (sin password ni personal grant)
        DB::table('oauth_clients')->insert([
            'id' => 3,
            'name' => 'External System Access Client',
            'secret' => 'P8ygv67E5AvG4gae6N3n7llOTpw4N3dysBA4EPdv',
            'provider' => null,
            'redirect' => 'http://localhost:3000/sgp/callback',
            'personal_access_client' => 0,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => '2023-07-17 18:19:14',
            'updated_at' => '2023-07-17 18:19:14'
        ]);
    }
}
