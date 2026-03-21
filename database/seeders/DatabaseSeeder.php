<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            TestDataSeeder::class,
        ]);

        // Créer un utilisateur pour se connecter
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer un utilisateur agent pour tester
        User::create([
            'name' => 'Agent',
            'email' => 'agent@agent.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'email_verified_at' => now(),
        ]);
    }
}
