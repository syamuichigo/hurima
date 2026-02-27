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
            [
                'user_id' => 1,
                'image' => 'image/top.jpg',
                'name' => 'user1',
                'postcode' => '123-4567',
                'address' => '東京都テスト町1-5-1',
                'building' => 'テストビル10階',
            ],
            [
                'user_id' => 2,
                'image' => 'image/top.jpg',
                'name' => 'user2',
                'postcode' => '234-5678',
                'address' => '東京都テスト町2-3-4',
                'building' => 'テストビル20階',
            ],
            [
                'user_id' => 3,
                'image' => 'image/top.jpg',
                'name' => 'user3',
                'postcode' => '345-6789',
                'address' => '東京都テスト町3-7-8',
                'building' => 'テストビル30階',
            ],
        ]);
    }
}
