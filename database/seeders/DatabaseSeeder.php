<?php

namespace Database\Seeders;

use App\Models\Tamu;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RolePermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);


        Tamu::create([
            'nama' => "Andi",
            'no_tlpn' => "0989877779923",
            'alamat' => 'Sukarisk,sudamulih',
            'email' => 'andi@gmail.com', // 3 digit di email
            'kota' => 'Pangangandaran',
            'no_identitas' => '98992399423',
        ]);

    
    }
}
