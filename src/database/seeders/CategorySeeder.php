<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'ファッション',
        ]);
        DB::table('categories')->insert([
            'name' => '家電',
        ]);
        DB::table('categories')->insert([
            'name' => 'インテリア',
        ]);
        DB::table('categories')->insert([
            'name' => 'レディース',
        ]);
        DB::table('categories')->insert([
            'name' => 'メンズ',
        ]);
        DB::table('categories')->insert([
            'name' => 'コスメ',
        ]);
        DB::table('categories')->insert([
            'name' => '本',
        ]);
        DB::table('categories')->insert([
            'name' => 'ゲーム',
        ]);
        DB::table('categories')->insert([
            'name' => 'スポーツ',
        ]);
        DB::table('categories')->insert([
            'name' => 'キッチン',
        ]);
        DB::table('categories')->insert([
            'name' => 'ハンドメイド',
        ]);
        DB::table('categories')->insert([
            'name' => 'アクセサリー',
        ]);
        DB::table('categories')->insert([
            'name' => 'おもちゃ',
        ]);
        DB::table('categories')->insert([
            'name' => 'ベビー・キッズ',
        ]);
    }
}
