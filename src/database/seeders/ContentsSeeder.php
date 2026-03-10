<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contents')->insert([
            'category_id' => 12, // アクセサリー
            'image' => 'images/sample/Clock.jpg',
            'name' => '腕時計',
            'brand' => 'Rolax',
            'price' => 15000,
            'info' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
        ]);

        DB::table('contents')->insert([
            'category_id' => 2, // 家電
            'image' => 'images/sample/HDD.jpg',
            'name' => 'HDD',
            'brand' => '西芝',
            'price' => 5000,
            'info' => '高速で信頼性ンの高いハードディスク',
            'condition_id' => 2,
        ]);

        DB::table('contents')->insert([
            'category_id' => 10, // キッチン
            'image' => 'images/sample/Onion.jpg',
            'name' => '玉ねぎ３束',
            'brand' => 'なし',
            'price' => 300,
            'info' => '新鮮な玉ねぎ３束のセット',
            'condition_id' => 3,
        ]);

        DB::table('contents')->insert([
            'category_id' => 1, // ファッション
            'image' => 'images/sample/Shoes.jpg',
            'name' => '革靴',
            'price' => 4000,
            'info' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
        ]);

        DB::table('contents')->insert([
            'category_id' => 2, // 家電
            'image' => 'images/sample/Pc.jpg',
            'name' => 'ノートPC',
            'price' => 45000,
            'info' => '高性能なノートパソコン',
            'condition_id' => 1,
        ]);

        DB::table('contents')->insert([
            'category_id' => 2, // 家電
            'image' => 'images/sample/Mic.jpg',
            'name' => 'マイク',
            'brand' => 'なし',
            'price' => 8000,
            'info' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
        ]);

        DB::table('contents')->insert([
            'category_id' => 1, // ファッション
            'image' => 'images/sample/bag.jpg',
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'info' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3,
        ]);

        DB::table('contents')->insert([
            'category_id' => 10, // キッチン
            'image' => 'images/sample/Tumbler.jpg',
            'name' => 'タンブラー',
            'brand' => 'なし',
            'price' => 500,
            'info' => '使いやすいタンブラー',
            'condition_id' => 4,
        ]);

        DB::table('contents')->insert([
            'category_id' => 10, // キッチン
            'image' => 'images/sample/Mill.jpg',
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'price' => 4000,
            'info' => '手動のコーヒーミル',
            'condition_id' => 1,
        ]);

        DB::table('contents')->insert([
            'category_id' => 6, // コスメ
            'image' => 'images/sample/Make.jpg',
            'name' => 'マイクセット',
            'price' => 2500,
            'info' => '便利なメイクアップセット',
            'condition_id' => 2,
        ]);
    }
}
