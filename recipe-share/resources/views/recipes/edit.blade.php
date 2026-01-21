@extends('layouts.app')

@section('title', 'レシピ編集')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">レシピ編集</h1>

    <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data"
          x-data="{
              ingredients: {{ json_encode($recipe->ingredients->map(fn($i) => ['name' => $i->name, 'quantity' => $i->quantity])->toArray()) }},
              steps: {{ json_encode($recipe->steps->map(fn($s) => ['description' => $s->description])->toArray()) }}
          }">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <!-- レシピ名 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">レシピ名 <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $recipe->title) }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 説明 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">説明 <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">{{ old('description', $recipe->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- カテゴリー -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">カテゴリー <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $recipe->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- タグ -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">タグ</label>
                <div class="flex flex-wrap gap-3">
                    @php
                        $selectedTags = old('tags', $recipe->tags->pluck('id')->toArray());
                    @endphp
                    @foreach($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                   class="text-orange-500 focus:ring-orange-500 rounded">
                            <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 調理時間・人数・難易度 -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">調理時間 <span class="text-red-500">*</span></label>
                    <div class="flex items-center">
                        <input type="number" name="cooking_time" value="{{ old('cooking_time', $recipe->cooking_time) }}" min="1" max="999"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <span class="ml-2 text-gray-600">分</span>
                    </div>
                    @error('cooking_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">人数 <span class="text-red-500">*</span></label>
                    <div class="flex items-center">
                        <input type="number" name="servings" value="{{ old('servings', $recipe->servings) }}" min="1" max="10"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <span class="ml-2 text-gray-600">人分</span>
                    </div>
                    @error('servings')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">難易度 <span class="text-red-500">*</span></label>
                    <select name="difficulty" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="easy" {{ old('difficulty', $recipe->difficulty) == 'easy' ? 'selected' : '' }}>簡単</option>
                        <option value="medium" {{ old('difficulty', $recipe->difficulty) == 'medium' ? 'selected' : '' }}>普通</option>
                        <option value="hard" {{ old('difficulty', $recipe->difficulty) == 'hard' ? 'selected' : '' }}>難しい</option>
                    </select>
                    @error('difficulty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 現在のメイン画像 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">現在のメイン画像</label>
                <img src="{{ asset('storage/' . $recipe->main_image) }}" alt="現在のメイン画像"
                     class="w-48 h-32 object-cover rounded-lg mb-2"
                     onerror="this.src='https://via.placeholder.com/200x150?text=No+Image'">
            </div>

            <!-- メイン画像変更 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">メイン画像を変更</label>
                <input type="file" name="main_image" accept="image/jpeg,image/png"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-sm text-gray-500 mt-1">変更しない場合は空のままにしてください</p>
                @error('main_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- サブ画像 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">サブ画像を変更（最大3枚）</label>
                @if($recipe->images->count() > 0)
                    <div class="flex gap-2 mb-2">
                        @foreach($recipe->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="サブ画像"
                                 class="w-24 h-24 object-cover rounded-lg"
                                 onerror="this.src='https://via.placeholder.com/100?text=No+Image'">
                        @endforeach
                    </div>
                @endif
                <input type="file" name="sub_images[]" accept="image/jpeg,image/png" multiple
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-sm text-gray-500 mt-1">新しい画像をアップロードすると既存の画像は置き換えられます</p>
            </div>

            <!-- 材料 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">材料 <span class="text-red-500">*</span></label>
                <template x-for="(ingredient, index) in ingredients" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="'ingredients[' + index + '][name]'" x-model="ingredient.name"
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="材料名">
                        <input type="text" :name="'ingredients[' + index + '][quantity]'" x-model="ingredient.quantity"
                               class="w-32 border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="分量">
                        <button type="button" @click="ingredients.splice(index, 1)" x-show="ingredients.length > 1"
                                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="ingredients.push({ name: '', quantity: '' })"
                        class="mt-2 text-orange-500 hover:text-orange-600 text-sm font-medium">
                    + 材料を追加
                </button>
            </div>

            <!-- 作り方 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">作り方 <span class="text-red-500">*</span></label>
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex gap-2 mb-2">
                        <span class="flex-shrink-0 w-8 h-10 bg-orange-100 text-orange-600 rounded flex items-center justify-center font-medium"
                              x-text="index + 1"></span>
                        <textarea :name="'steps[' + index + '][description]'" x-model="step.description" rows="2"
                                  class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="手順を入力..."></textarea>
                        <button type="button" @click="steps.splice(index, 1)" x-show="steps.length > 1"
                                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-md self-start">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="steps.push({ description: '' })"
                        class="mt-2 text-orange-500 hover:text-orange-600 text-sm font-medium">
                    + ステップを追加
                </button>
            </div>

            <!-- 公開設定 -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_public" value="0" {{ old('is_public', $recipe->is_public) ? '' : 'checked' }}
                           class="text-orange-500 focus:ring-orange-500 rounded">
                    <span class="ml-2 text-sm text-gray-700">非公開にする</span>
                </label>
            </div>

            <!-- 送信ボタン -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-orange-500 text-white py-3 rounded-md hover:bg-orange-600 transition font-medium">
                    更新する
                </button>
                <a href="{{ route('recipes.show', $recipe) }}" class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition text-center">
                    キャンセル
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
