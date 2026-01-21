@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- レシピヘッダー -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $recipe->title }}</h1>
            <div class="flex items-center text-gray-600">
                @if($recipe->user->profile_image)
                    <img src="{{ asset('storage/' . $recipe->user->profile_image) }}" alt="{{ $recipe->user->name }}"
                         class="w-8 h-8 rounded-full mr-2 object-cover">
                @else
                    <div class="w-8 h-8 rounded-full bg-gray-300 mr-2 flex items-center justify-center">
                        <span class="text-gray-600 text-sm">{{ mb_substr($recipe->user->name, 0, 1) }}</span>
                    </div>
                @endif
                <span>{{ $recipe->user->name }}</span>
                <span class="mx-2">•</span>
                <span>{{ $recipe->created_at->format('Y/m/d') }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @auth
                <!-- お気に入りボタン -->
                <div x-data="{ favorited: {{ $isFavorited ? 'true' : 'false' }}, count: {{ $recipe->favorites_count ?? 0 }} }">
                    <button @click="
                        fetch(favorited ? '{{ route('favorites.destroy', $recipe) }}' : '{{ route('favorites.store', $recipe) }}', {
                            method: favorited ? 'DELETE' : 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            favorited = data.favorited;
                            count = data.count;
                        })
                    " class="flex items-center gap-1 px-4 py-2 border rounded-lg transition"
                       :class="favorited ? 'bg-red-50 border-red-300 text-red-500' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                        <svg class="w-5 h-5" :fill="favorited ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span x-text="count"></span>
                    </button>
                </div>

                @if(auth()->id() === $recipe->user_id)
                    <a href="{{ route('recipes.edit', $recipe) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        編集
                    </a>
                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 border border-red-300 rounded-lg text-red-500 hover:bg-red-50 transition">
                            削除
                        </button>
                    </form>
                @endif
            @else
                <span class="text-gray-500 flex items-center gap-1">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ $recipe->favorites_count ?? 0 }}
                </span>
            @endauth
        </div>
    </div>

    <!-- メイン画像 -->
    <div class="mb-6">
        <img src="{{ asset('storage/' . $recipe->main_image) }}" alt="{{ $recipe->title }}"
             class="w-full h-96 object-cover rounded-lg"
             onerror="this.src='https://via.placeholder.com/800x400?text=No+Image'">
    </div>

    <!-- サブ画像 -->
    @if($recipe->images->count() > 0)
        <div class="flex gap-4 mb-6 overflow-x-auto pb-2">
            @foreach($recipe->images as $image)
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="サブ画像"
                     class="w-32 h-32 object-cover rounded-lg flex-shrink-0"
                     onerror="this.src='https://via.placeholder.com/128?text=No+Image'">
            @endforeach
        </div>
    @endif

    <!-- レシピ情報 -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <p class="text-gray-700 mb-4">{{ $recipe->description }}</p>

        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $recipe->cooking_time }}分
            </span>
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ $recipe->servings }}人分
            </span>
            <span class="flex items-center">
                難易度: {{ $recipe->difficulty_label }}
            </span>
            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded">
                {{ $recipe->category->name }}
            </span>
        </div>

        @if($recipe->tags->count() > 0)
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach($recipe->tags as $tag)
                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-4">
            @php
                $avgRating = $recipe->reviews_avg_rating ?? 0;
            @endphp
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $avgRating)
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endif
            @endfor
            <span class="ml-2 text-gray-600">({{ number_format($avgRating, 1) }}) {{ $recipe->reviews->count() }}件のレビュー</span>
        </div>
    </div>

    <!-- 材料 -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">材料（{{ $recipe->servings }}人分）</h2>
        <ul class="space-y-2">
            @foreach($recipe->ingredients as $ingredient)
                <li class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-700">{{ $ingredient->name }}</span>
                    <span class="text-gray-500">{{ $ingredient->quantity }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- 作り方 -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">作り方</h2>
        <ol class="space-y-4">
            @foreach($recipe->steps as $step)
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold">
                        {{ $step->step_number }}
                    </span>
                    <p class="text-gray-700 pt-1">{{ $step->description }}</p>
                </li>
            @endforeach
        </ol>
    </div>

    <!-- レビューセクション -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">レビュー</h2>

        @auth
            @if(!$userReview)
                <!-- レビュー投稿フォーム -->
                <form action="{{ route('reviews.store', $recipe) }}" method="POST" class="mb-6 p-4 bg-gray-50 rounded-lg">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">評価</label>
                        <div class="flex gap-1" x-data="{ rating: 0 }">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}"
                                        class="text-2xl focus:outline-none"
                                        :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'">
                                    ★
                                </button>
                            @endfor
                            <input type="hidden" name="rating" x-model="rating">
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">コメント（任意）</label>
                        <textarea name="comment" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="レビューコメントを入力...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 transition">
                        レビューを投稿
                    </button>
                </form>
            @endif
        @else
            <p class="mb-6 p-4 bg-gray-50 rounded-lg text-gray-600">
                レビューを投稿するには<a href="{{ route('login') }}" class="text-orange-500 hover:underline">ログイン</a>してください
            </p>
        @endauth

        <!-- レビュー一覧 -->
        <div class="space-y-4">
            @forelse($recipe->reviews as $review)
                <div class="border-b border-gray-100 pb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2">
                            @if($review->user->profile_image)
                                <img src="{{ asset('storage/' . $review->user->profile_image) }}" alt="{{ $review->user->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 text-sm">{{ mb_substr($review->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ★
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">{{ $review->created_at->format('Y/m/d') }}</span>
                            @if(auth()->id() === $review->user_id)
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-sm hover:underline" onclick="return confirm('削除しますか？')">
                                        削除
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="mt-2 text-gray-700 ml-10">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">まだレビューがありません</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
