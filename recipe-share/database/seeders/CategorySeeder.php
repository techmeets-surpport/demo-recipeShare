<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => '和食', 'created_at' => now()],
            ['name' => '洋食', 'created_at' => now()],
            ['name' => '中華', 'created_at' => now()],
            ['name' => 'イタリアン', 'created_at' => now()],
            ['name' => 'その他', 'created_at' => now()],
        ]);
    }
}
