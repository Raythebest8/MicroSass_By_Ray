<?php


namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['libelle' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'user', 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'manager', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}