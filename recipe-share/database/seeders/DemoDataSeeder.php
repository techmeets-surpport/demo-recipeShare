<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Ingredient;
use App\Models\Step;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // デモユーザー作成
        $users = [
            [
                'name' => '田中シェフ',
                'email' => 'tanaka@demo.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => '鈴木クッキング',
                'email' => 'suzuki@demo.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => '山田キッチン',
                'email' => 'yamada@demo.com',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        $tanaka = User::where('email', 'tanaka@demo.com')->first();
        $suzuki = User::where('email', 'suzuki@demo.com')->first();
        $yamada = User::where('email', 'yamada@demo.com')->first();

        // カテゴリとタグ取得
        $washoku = Category::where('name', '和食')->first();
        $yoshoku = Category::where('name', '洋食')->first();
        $italian = Category::where('name', 'イタリアン')->first();
        $chinese = Category::where('name', '中華')->first();

        // デモレシピ
        $recipes = [
            [
                'user' => $tanaka,
                'category' => $washoku,
                'title' => '基本の肉じゃが',
                'description' => '家庭の定番料理、肉じゃがです。ホクホクのじゃがいもと甘辛い味付けがご飯によく合います。',
                'cooking_time' => 40,
                'servings' => 4,
                'difficulty' => 'easy',
                'ingredients' => [
                    ['name' => '豚バラ肉', 'amount' => '200g'],
                    ['name' => 'じゃがいも', 'amount' => '4個'],
                    ['name' => '玉ねぎ', 'amount' => '1個'],
                    ['name' => 'にんじん', 'amount' => '1本'],
                    ['name' => '醤油', 'amount' => '大さじ3'],
                    ['name' => '砂糖', 'amount' => '大さじ2'],
                    ['name' => 'みりん', 'amount' => '大さじ2'],
                    ['name' => 'だし汁', 'amount' => '400ml'],
                ],
                'steps' => [
                    'じゃがいも、玉ねぎ、にんじんを一口大に切る',
                    '鍋に油を熱し、豚肉を炒める',
                    '野菜を加えて軽く炒める',
                    'だし汁と調味料を加え、落し蓋をして20分煮る',
                    '味が染みたら完成',
                ],
                'tags' => ['簡単', '定番'],
            ],
            [
                'user' => $tanaka,
                'category' => $washoku,
                'title' => 'だし巻き卵',
                'description' => 'ふわふわでジューシーなだし巻き卵。お弁当にもおつまみにも最適です。',
                'cooking_time' => 15,
                'servings' => 2,
                'difficulty' => 'medium',
                'ingredients' => [
                    ['name' => '卵', 'amount' => '3個'],
                    ['name' => 'だし汁', 'amount' => '50ml'],
                    ['name' => '薄口醤油', 'amount' => '小さじ1'],
                    ['name' => '砂糖', 'amount' => '小さじ1'],
                    ['name' => 'サラダ油', 'amount' => '適量'],
                ],
                'steps' => [
                    '卵を溶き、だし汁と調味料を混ぜる',
                    '卵焼き器を熱し、油をなじませる',
                    '卵液を少量流し入れ、半熟で巻く',
                    '繰り返し巻いていく',
                    '形を整えて完成',
                ],
                'tags' => ['定番', 'お弁当'],
            ],
            [
                'user' => $suzuki,
                'category' => $yoshoku,
                'title' => '本格ハンバーグ',
                'description' => 'ジューシーで肉汁たっぷりのハンバーグ。デミグラスソースとの相性抜群です。',
                'cooking_time' => 45,
                'servings' => 4,
                'difficulty' => 'medium',
                'ingredients' => [
                    ['name' => '合いびき肉', 'amount' => '400g'],
                    ['name' => '玉ねぎ', 'amount' => '1/2個'],
                    ['name' => 'パン粉', 'amount' => '1/2カップ'],
                    ['name' => '牛乳', 'amount' => '大さじ3'],
                    ['name' => '卵', 'amount' => '1個'],
                    ['name' => '塩コショウ', 'amount' => '適量'],
                    ['name' => 'ナツメグ', 'amount' => '少々'],
                ],
                'steps' => [
                    '玉ねぎをみじん切りにし、炒めて冷ます',
                    'パン粉を牛乳で湿らせる',
                    '全ての材料をよく混ぜてこねる',
                    '成形し、中央をくぼませる',
                    'フライパンで両面を焼き、蓋をして蒸し焼きにする',
                ],
                'tags' => ['ボリューム', '定番'],
            ],
            [
                'user' => $suzuki,
                'category' => $italian,
                'title' => 'カルボナーラ',
                'description' => '濃厚クリーミーな本格カルボナーラ。卵とチーズのコクがたまりません。',
                'cooking_time' => 20,
                'servings' => 2,
                'difficulty' => 'medium',
                'ingredients' => [
                    ['name' => 'スパゲッティ', 'amount' => '200g'],
                    ['name' => 'ベーコン', 'amount' => '100g'],
                    ['name' => '卵黄', 'amount' => '2個'],
                    ['name' => '全卵', 'amount' => '1個'],
                    ['name' => 'パルメザンチーズ', 'amount' => '50g'],
                    ['name' => '黒コショウ', 'amount' => '適量'],
                    ['name' => 'オリーブオイル', 'amount' => '大さじ1'],
                ],
                'steps' => [
                    'パスタを茹でる',
                    'ベーコンをカリカリに炒める',
                    '卵、チーズ、黒コショウを混ぜてソースを作る',
                    '茹でたパスタとベーコンを合わせる',
                    '火を止めてソースを絡める（卵が固まらないように注意）',
                ],
                'tags' => ['パスタ', '簡単'],
            ],
            [
                'user' => $yamada,
                'category' => $chinese,
                'title' => '本格麻婆豆腐',
                'description' => 'ピリ辛で本格的な麻婆豆腐。花椒の香りが食欲をそそります。',
                'cooking_time' => 25,
                'servings' => 3,
                'difficulty' => 'medium',
                'ingredients' => [
                    ['name' => '絹豆腐', 'amount' => '1丁'],
                    ['name' => '豚ひき肉', 'amount' => '150g'],
                    ['name' => '長ねぎ', 'amount' => '1/2本'],
                    ['name' => '豆板醤', 'amount' => '大さじ1'],
                    ['name' => '甜麺醤', 'amount' => '大さじ1'],
                    ['name' => '鶏がらスープ', 'amount' => '200ml'],
                    ['name' => '花椒', 'amount' => '適量'],
                    ['name' => '片栗粉', 'amount' => '大さじ1'],
                ],
                'steps' => [
                    '豆腐を角切りにし、下茹でする',
                    '中華鍋で豚ひき肉を炒める',
                    '豆板醤、甜麺醤を加えて香りを出す',
                    'スープを加え、豆腐を入れて煮る',
                    '水溶き片栗粉でとろみをつけ、花椒を振る',
                ],
                'tags' => ['ピリ辛', 'ご飯に合う'],
            ],
            [
                'user' => $yamada,
                'category' => $yoshoku,
                'title' => 'チキン南蛮',
                'description' => 'サクサクの鶏肉に甘酢とタルタルソースが絶品。宮崎名物をおうちで再現。',
                'cooking_time' => 35,
                'servings' => 2,
                'difficulty' => 'medium',
                'ingredients' => [
                    ['name' => '鶏もも肉', 'amount' => '2枚'],
                    ['name' => '卵', 'amount' => '1個'],
                    ['name' => '小麦粉', 'amount' => '適量'],
                    ['name' => '酢', 'amount' => '大さじ3'],
                    ['name' => '醤油', 'amount' => '大さじ2'],
                    ['name' => '砂糖', 'amount' => '大さじ2'],
                    ['name' => 'ゆで卵', 'amount' => '2個'],
                    ['name' => 'マヨネーズ', 'amount' => '大さじ4'],
                ],
                'steps' => [
                    '鶏肉に小麦粉をまぶし、溶き卵をつけて揚げる',
                    '甘酢ダレ（酢、醤油、砂糖）を合わせて温める',
                    '揚げた鶏肉を甘酢に絡める',
                    'ゆで卵を刻んでマヨネーズと混ぜ、タルタルを作る',
                    '鶏肉にタルタルをかけて完成',
                ],
                'tags' => ['ボリューム', '揚げ物'],
            ],
        ];

        foreach ($recipes as $recipeData) {
            // レシピが既に存在するかチェック
            $existingRecipe = Recipe::where('title', $recipeData['title'])
                ->where('user_id', $recipeData['user']->id)
                ->first();

            // 既存レシピがあるが材料がない場合は削除して再作成
            if ($existingRecipe) {
                if ($existingRecipe->ingredients()->count() === 0) {
                    $existingRecipe->forceDelete();
                } else {
                    continue;
                }
            }

            // レシピ作成
            $recipe = Recipe::create([
                'user_id' => $recipeData['user']->id,
                'category_id' => $recipeData['category']->id,
                'title' => $recipeData['title'],
                'description' => $recipeData['description'],
                'cooking_time' => $recipeData['cooking_time'],
                'servings' => $recipeData['servings'],
                'difficulty' => $recipeData['difficulty'],
                'main_image' => 'recipes/demo-placeholder.jpg',
                'is_public' => true,
                'views_count' => rand(10, 500),
            ]);

            // 材料追加
            foreach ($recipeData['ingredients'] as $order => $ingredient) {
                Ingredient::create([
                    'recipe_id' => $recipe->id,
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['amount'],
                    'display_order' => $order + 1,
                ]);
            }

            // 手順追加
            foreach ($recipeData['steps'] as $order => $description) {
                Step::create([
                    'recipe_id' => $recipe->id,
                    'step_number' => $order + 1,
                    'description' => $description,
                ]);
            }

            // タグ追加
            $tagIds = Tag::whereIn('name', $recipeData['tags'])->pluck('id');
            $recipe->tags()->attach($tagIds);
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Demo users:');
        $this->command->info('  - tanaka@demo.com / password123');
        $this->command->info('  - suzuki@demo.com / password123');
        $this->command->info('  - yamada@demo.com / password123');
    }
}
