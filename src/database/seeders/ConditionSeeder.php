<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('condition')->insert([
            'name' => '良好',
        ]);
        DB::table('condition')->insert([
            'name' => '目立った傷や汚れなし',
        ]);
        DB::table('condition')->insert([
            'name' => 'やや傷や汚れあり',
        ]);
        DB::table('condition')->insert([
            'name' => '状態が悪い',
        ]);
    }
}
