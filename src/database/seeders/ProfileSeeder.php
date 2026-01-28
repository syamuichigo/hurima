<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            'user_id' => 1,
            'image' => 'image/top.jpg',
            'name' => 'test',
            'postcode' => '123-4567',
            'address' => '東京都テスト町1-5-1',
            'building' => 'テストビル10階',
        ]);
    }
}
