<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        
        $pemilik = User::create([
            'name' => 'pemilik',
            'username' => 'pemilik',
            'email' => 'pemilik@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('pemilik123') 
        ]);
        $pemilik->assignRole('pemilik');

        $resepsionis = User::create([
            'name' => 'resep',
            'username' => 'resep',
            'email' => 'resep@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('resep123') 
        ]);
        $resepsionis->assignRole('resepsionis');



        
    }
}
