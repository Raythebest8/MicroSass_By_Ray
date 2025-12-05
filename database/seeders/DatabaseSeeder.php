<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder; // Assurez-vous que le namespace est correct
use App\Models\Role; // Assurez-vous que ce modèle existe

class DatabaseSeeder extends Seeder
{
    // C'est un trait, il est bien placé
    use WithoutModelEvents; 

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);
        
    
        // 1. Créer un utilisateur 'user' standard
        User::factory()->create([
            'nom' => 'Kokodoko',
            'prenom' => 'raymond',
            'email' => 'kokodokoraymond@gmail.com',
            'password' => bcrypt('raythebest00'),
            'role' => 'user', 
        ]);
        
        // 2. Créer un utilisateur admin par défaut
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nom' => 'kokodoko',
                'prenom' => 'raymond',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin1234'),
                'role' => 'admin',
            ]
        );
        
    }
}