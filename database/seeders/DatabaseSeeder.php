<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zone;
use App\Models\Location;
use App\Models\LocationZone;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory(5)->create();
        // Zone::factory(5)->create();
        // Location::factory(5)->create();
        LocationZone::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
