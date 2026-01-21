@props(['recipe'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
    <a href="{{ route('recipes.show', $recipe) }}">
        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
            <img src="{{ asset('storage/' . $recipe->main_image) }}"
                 alt="{{ $recipe->title }}"
                 class="w-full h-48 object-cover"
                 onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
        </div>
    </a>
    <div class="p-4">
        <a href="{{ route('recipes.show', $recipe) }}" class="block">
            <h3 class="text-lg font-semibold text-gray-900 hover:text-orange-500 transition line-clamp-1">
                {{ $recipe->title }}
            </h3>
        </a>
        <p class="text-sm text-gray-600 mt-1">
            {{ $recipe->user->name ?? '不明なユーザー' }}
        </p>
        <div class="flex items-center justify-between mt-3 text-sm text-gray-500">
            <div class="flex items-center space-x-3">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $recipe->cooking_time }}分
                </span>
                <span class="flex items-center">
                    @php
                        $avgRating = $recipe->reviews_avg_rating ?? 0;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $avgRating)
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </span>
            </div>
            <span class="flex items-center text-red-400">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
                {{ $recipe->favorites_count ?? 0 }}
            </span>
        </div>
    </div>
</div>
