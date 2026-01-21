@extends('layouts.app')

@section('title', 'レシピ投稿')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">レシピ投稿</h1>

    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data"
          x-data="{
              ingredients: [{ name: '', quantity: '' }],
              steps: [{ description: '' }]
          }">
        @csrf

        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <!-- レシピ名 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">レシピ名 <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="例：簡単豚キムチ炒め">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 説明 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">説明 <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="レシピの説明を入力...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- カテゴリー -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">カテゴリー <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">選択してください</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    @foreach($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
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
                        <input type="number" name="cooking_time" value="{{ old('cooking_time') }}" min="1" max="999"
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
                        <input type="number" name="servings" value="{{ old('servings', 2) }}" min="1" max="10"
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
                        <option value="">選択してください</option>
                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>簡単</option>
                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>普通</option>
                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>難しい</option>
                    </select>
                    @error('difficulty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- メイン画像 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">メイン画像 <span class="text-red-500">*</span></label>
                <input type="file" name="main_image" accept="image/jpeg,image/png"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-sm text-gray-500 mt-1">JPEG、PNG形式、最大2MB</p>
                @error('main_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- サブ画像 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">サブ画像（最大3枚）</label>
                <input type="file" name="sub_images[]" accept="image/jpeg,image/png" multiple
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-sm text-gray-500 mt-1">JPEG、PNG形式、各最大2MB</p>
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
                @error('ingredients')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                @error('steps')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 公開設定 -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_public" value="0" {{ old('is_public', true) ? '' : 'checked' }}
                           class="text-orange-500 focus:ring-orange-500 rounded">
                    <span class="ml-2 text-sm text-gray-700">非公開にする</span>
                </label>
            </div>

            <!-- 送信ボタン -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-orange-500 text-white py-3 rounded-md hover:bg-orange-600 transition font-medium">
                    投稿する
                </button>
                <a href="{{ route('welcome') }}" class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition text-center">
                    キャンセル
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
