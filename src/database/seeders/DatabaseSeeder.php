<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(ProfileSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ConditionSeeder::class);
        $this->call(ContentsSeeder::class);
        $this->call(ListingSeeder::class);
    }
}
