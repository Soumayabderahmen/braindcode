<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        // Create a user
        $user = User::factory()->create([
            
            'name' => 'oumaima jlidi',
            'email' => 'jlidioumaima01@gmail.com',
            'password' => bcrypt('AO09!lve'),
            'role'=>'admin'
        ]);

        // Attach role to user
      //  $user->roles()->attach($adminRole->id);
    }
}