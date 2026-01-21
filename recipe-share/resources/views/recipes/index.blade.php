@extends('layouts.app')

@section('title', 'レシピ一覧')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- サイドバー（フィルター） -->
        <aside class="lg:w-64 flex-shrink-0">
            <form action="{{ route('recipes.index') }}" method="GET" x-data="{ open: window.innerWidth >= 1024 }">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center lg:hidden mb-4">
                        <h3 class="font-bold text-lg">フィルター</h3>
                        <button type="button" @click="open = !open" class="text-gray-500">
                            <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" x-cloak class="space-y-6">
                        <!-- キーワード -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">キーワード</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="検索...">
                        </div>

                        <!-- カテゴリー -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">カテゴリー</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="category_id" value="" {{ !request('category_id') ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-700">すべて</span>
                                </label>
                                @foreach($categories as $category)
                                    <label class="flex items-center">
                                        <input type="radio" name="category_id" value="{{ $category->id }}"
                                               {{ request('category_id') == $category->id ? 'checked' : '' }}
                                               class="text-orange-500 focus:ring-orange-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- タグ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">タグ</label>
                            <div class="space-y-2">
                                @foreach($tags as $tag)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                               {{ in_array($tag->id, request('tags', [])) ? 'checked' : '' }}
                                               class="text-orange-500 focus:ring-orange-500 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 調理時間 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">調理時間</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="cooking_time" value="" {{ !request('cooking_time') ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-700">すべて</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="cooking_time" value="15" {{ request('cooking_time') == '15' ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-700">15分以内</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="cooking_time" value="30" {{ request('cooking_time') == '30' ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-700">30分以内</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="cooking_time" value="60" {{ request('cooking_time') == '60' ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-700">60分以内</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="cooking_time" value="60+" {{ request('cooking_time') == '60+' ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-700">60分以上</span>
                                </label>
                            </div>
                        </div>

                        <!-- 難易度 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">難易度</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="difficulty[]" value="easy"
                                           {{ in_array('easy', request('difficulty', [])) ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500 rounded">
                                    <span class="ml-2 text-sm text-gray-700">簡単</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="difficulty[]" value="medium"
                                           {{ in_array('medium', request('difficulty', [])) ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500 rounded">
                                    <span class="ml-2 text-sm text-gray-700">普通</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="difficulty[]" value="hard"
                                           {{ in_array('hard', request('difficulty', [])) ? 'checked' : '' }}
                                           class="text-orange-500 focus:ring-orange-500 rounded">
                                    <span class="ml-2 text-sm text-gray-700">難しい</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 transition">
                                検索
                            </button>
                            <a href="{{ route('recipes.index') }}" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-md hover:bg-gray-300 transition text-center">
                                リセット
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </aside>

        <!-- メインコンテンツ -->
        <main class="flex-1">
            <!-- ソート・件数 -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">検索結果: {{ $recipes->total() }}件</p>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">並び替え:</label>
                    <select onchange="location.href=this.value" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'new']) }}" {{ request('sort', 'new') == 'new' ? 'selected' : '' }}>新着順</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>人気順</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ request('sort') == 'rating' ? 'selected' : '' }}>評価順</option>
                    </select>
                </div>
            </div>

            <!-- レシピ一覧 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($recipes as $recipe)
                    @include('components.recipe-card', ['recipe' => $recipe])
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">該当するレシピが見つかりませんでした</p>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-8">
                {{ $recipes->links() }}
            </div>
        </main>
    </div>
</div>
@endsection
