<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('listing')->insert([
            ['user_id' => 1, 'content_id' => 1],
            ['user_id' => 1, 'content_id' => 2],
            ['user_id' => 1, 'content_id' => 3],
            ['user_id' => 1, 'content_id' => 4],
            ['user_id' => 1, 'content_id' => 5],
            ['user_id' => 2, 'content_id' => 6],
            ['user_id' => 2, 'content_id' => 7],
            ['user_id' => 2, 'content_id' => 8],
            ['user_id' => 2, 'content_id' => 9],
            ['user_id' => 2, 'content_id' => 10],
        ]);
    }
}
