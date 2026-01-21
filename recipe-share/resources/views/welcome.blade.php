@extends('layouts.app')

@section('title', 'トップページ')

@section('content')
    <!-- ヒーローセクション -->
    <section class="relative bg-gradient-to-r from-orange-500 to-red-500 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">美味しいレシピを共有しよう</h1>
            <p class="text-xl mb-8">あなたのお気に入りのレシピを投稿して、みんなと共有しましょう</p>
            <form action="{{ route('recipes.index') }}" method="GET" class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" name="keyword"
                           class="w-full pl-12 pr-4 py-4 text-gray-900 rounded-full focus:ring-2 focus:ring-orange-300 focus:outline-none"
                           placeholder="レシピを検索...">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="absolute right-2 top-2 bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transition">
                        検索
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- 新着レシピセクション -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">新着レシピ</h2>
                <a href="{{ route('recipes.index', ['sort' => 'new']) }}" class="text-orange-500 hover:text-orange-600 transition">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($newRecipes as $recipe)
                    @include('components.recipe-card', ['recipe' => $recipe])
                @empty
                    <p class="text-gray-500 col-span-3 text-center py-8">レシピがまだありません</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 人気レシピセクション -->
    <section class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">人気レシピ</h2>
                <a href="{{ route('recipes.index', ['sort' => 'popular']) }}" class="text-orange-500 hover:text-orange-600 transition">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($popularRecipes as $recipe)
                    @include('components.recipe-card', ['recipe' => $recipe])
                @empty
                    <p class="text-gray-500 col-span-3 text-center py-8">レシピがまだありません</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
