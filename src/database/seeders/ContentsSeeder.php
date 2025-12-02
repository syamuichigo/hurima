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
            'image' => 'storage/images/Clock.jpg',
            'name' => '腕時計',
            'brand' => 'Rolax',
            'price' => 15000,
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'info' => '良好',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/HDD.jpg',
            'name' => 'HDD',
            'brand' => '西芝',
            'price' => 5000,
            'detail' => '高速で信頼性ンの高いハードディスク',
            'info' => '目立った傷や汚れなし',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Onion.jpg',
            'name' => '玉ねぎ３束',
            'brand' => 'なし',
            'price' => 300,
            'detail' => '新鮮な玉ねぎ３束のセット',
            'info' => 'やや傷や汚れあり',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Shoes.jpg',
            'name' => '革靴',
            'price' => 4000,
            'detail' => 'クラシックなデザインの革靴',
            'info' => '状態が悪い',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Pc.jpg',
            'name' => 'ノートPC',
            'price' => 45000,
            'detail' => '高性能なノートパソコン',
            'info' => '良好',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Mic.jpg',
            'name' => 'マイク',
            'brand' => 'なし',
            'price' => 8000,
            'detail' => '高音質のレコーディング用マイク',
            'info' => '目立った傷や汚れなし',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Bag.jpg',
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'detail' => 'おしゃれなショルダーバッグ',
            'info' => 'やや傷や汚れあり',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Tumbler.jpg',
            'name' => 'タンブラー',
            'brand' => 'なし',
            'price' => 500,
            'detail' => '使いやすいタンブラー',
            'info' => '状態が悪い',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Mill.jpg',
            'name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'price' => 4000,
            'detail' => '手動のコーヒーミル',
            'info' => '良好',
        ]);

        DB::table('contents')->insert([
            'image' => 'storage/images/Make.jpg',
            'name' => 'マイクセット',
            'price' => 2500,
            'detail' => '便利なメイクアップセット',
            'info' => '目立った傷や汚れなし',
        ]);
    }
}
