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
            'image' => 'storage/image/Clock.jpg',
            'name' => '腕時計',
            'brand' => 'Rolax',
            'price' => 15000,
            'info' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
        ]);

        DB::table('contents')->insert([
            'category_id' => 2, // 家電
            'image' => 'storage/image/HDD.jpg',
            'name' => 'HDD',
            'brand' => '西芝',
            'price' => 5000,
            'info' => '高速で信頼性ンの高いハードディスク',
            'condition_id' => 2,
        ]);

        DB::table('contents')->insert([
            'category_id' => 10, // キッチン
            'image' => 'storage/image/Onion.jpg',
            'name' => '玉ねぎ３束',
            'brand' => 'なし',
            'price' => 300,
            'info' => '新鮮な玉ねぎ３束のセット',
            'condition_id' => 3,
        ]);

        DB::table('contents')->insert([
            'category_id' => 1, // ファッション
            'image' => 'storage/image/Shoes.jpg',
            'name' => '革靴',
            'price' => 4000,
            'info' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
        ]);

        DB::table('contents')->insert([
            'category_id' => 2, // 家電
            'image' => 'storage/image/Pc.jpg',
            'name' => 'ノートPC',
            'price' => 45000,
            'info' => '高性能なノートパソコン',
            'condition_id' => 1,
        ]);

        DB::table('contents')->insert([
            'category_id' => 2, // 家電
            'image' => 'storage/image/Mic.jpg',
            'name' => 'マイク',
            'brand' => 'なし',
            'price' => 8000,
            'info' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
        ]);

        DB::table('contents')->insert([
            'category_id' => 1, // ファッション
            'image' => 'storage/image/bag.jpg',
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'info' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3,
        ]);

        DB::table('contents')->insert([
            'category_id' => 10, // キッチン
            'image' => 'storage/image/Tumbler.jpg',
            'name' => 'タンブラー',
            'brand' => 'なし',
            'price' => 500,
            'info' => '使いやすいタンブラー',
            'condition_id' => 4,
        ]);

        DB::table('contents')->insert([
            'category_id' => 10, // キッチン
            'image' => 'storage/image/Mill.jpg',
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'price' => 4000,
            'info' => '手動のコーヒーミル',
            'condition_id' => 1,
        ]);

        DB::table('contents')->insert([
            'category_id' => 6, // コスメ
            'image' => 'storage/image/Make.jpg',
            'name' => 'マイクセット',
            'price' => 2500,
            'info' => '便利なメイクアップセット',
            'condition_id' => 2,
        ]);
    }
}
