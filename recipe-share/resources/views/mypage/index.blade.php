@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- プロフィールセクション -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex items-start gap-6">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover">
            @else
                <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-600 text-3xl">{{ mb_substr($user->name, 0, 1) }}</span>
                </div>
            @endif
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        @if($user->bio)
                            <p class="text-gray-600 mt-2">{{ $user->bio }}</p>
                        @endif
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        プロフィール編集
                    </a>
                </div>

                <!-- 統計情報 -->
                <div class="flex gap-8 mt-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-500">{{ $stats['recipes_count'] }}</p>
                        <p class="text-sm text-gray-600">投稿レシピ</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-500">{{ $stats['favorites_received'] }}</p>
                        <p class="text-sm text-gray-600">お気に入りされた数</p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center text-2xl font-bold text-orange-500">
                            <span class="text-yellow-400 mr-1">★</span>
                            {{ number_format($stats['average_rating'], 1) }}
                        </div>
                        <p class="text-sm text-gray-600">平均評価</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- タブセクション -->
    <div class="bg-white rounded-lg shadow">
        <!-- タブナビゲーション -->
        <div class="border-b border-gray-200">
            <nav class="flex">
                <a href="{{ route('mypage', ['tab' => 'public']) }}"
                   class="px-6 py-4 text-sm font-medium {{ $tab == 'public' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                    公開レシピ
                </a>
                <a href="{{ route('mypage', ['tab' => 'private']) }}"
                   class="px-6 py-4 text-sm font-medium {{ $tab == 'private' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                    非公開レシピ
                </a>
                <a href="{{ route('mypage', ['tab' => 'favorites']) }}"
                   class="px-6 py-4 text-sm font-medium {{ $tab == 'favorites' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                    お気に入り
                </a>
            </nav>
        </div>

        <!-- タブコンテンツ -->
        <div class="p-6">
            @if($recipes->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recipes as $recipe)
                        @include('components.recipe-card', ['recipe' => $recipe])
                    @endforeach
                </div>

                <!-- ページネーション -->
                <div class="mt-8">
                    {{ $recipes->appends(['tab' => $tab])->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">
                        @if($tab == 'public')
                            公開中のレシピはありません
                        @elseif($tab == 'private')
                            非公開のレシピはありません
                        @else
                            お気に入りのレシピはありません
                        @endif
                    </p>
                    @if($tab != 'favorites')
                        <a href="{{ route('recipes.create') }}" class="mt-4 inline-block bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                            レシピを投稿する
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
