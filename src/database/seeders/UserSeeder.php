<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'user2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'user3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
