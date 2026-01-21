<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    public function run()
    {
        DB::table('tags')->insert([
            ['name' => '肉料理', 'created_at' => now()],
            ['name' => '魚料理', 'created_at' => now()],
            ['name' => '野菜料理', 'created_at' => now()],
            ['name' => 'デザート', 'created_at' => now()],
            ['name' => '時短', 'created_at' => now()],
            ['name' => 'ヘルシー', 'created_at' => now()],
            ['name' => 'がっつり', 'created_at' => now()],
            ['name' => 'おつまみ', 'created_at' => now()],
        ]);
    }
}
