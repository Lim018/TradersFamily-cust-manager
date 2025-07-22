<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //     ]);
    // }

    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@customersync.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create agent users
        User::create([
            'name' => 'Agent 1',
            'email' => 'agent1@customersync.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'agent_code' => 'AG001'
        ]);

        User::create([
            'name' => 'Agent 2',
            'email' => 'agent2@customersync.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'agent_code' => 'AG002'
        ]);

        User::create([
            'name' => 'Agent 3',
            'email' => 'agent3@customersync.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'agent_code' => 'AG003'
        ]);
    }
}
