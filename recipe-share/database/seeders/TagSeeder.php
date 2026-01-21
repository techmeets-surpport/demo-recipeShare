<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            '肉料理', '魚料理', '野菜料理', 'デザート',
            '時短', 'ヘルシー', 'がっつり', 'おつまみ',
            '簡単', '定番', 'お弁当', 'ボリューム',
            'パスタ', 'ピリ辛', 'ご飯に合う', '揚げ物',
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
