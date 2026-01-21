@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">プロフィール編集</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <!-- プロフィール画像 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">プロフィール画像</label>
                <div class="flex items-center gap-4">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}"
                             class="w-20 h-20 rounded-full object-cover">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-600 text-2xl">{{ mb_substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <input type="file" name="profile_image" accept="image/jpeg,image/png"
                               class="border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <p class="text-sm text-gray-500 mt-1">JPEG、PNG形式、最大2MB</p>
                    </div>
                </div>
                @error('profile_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ユーザー名 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ユーザー名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 自己紹介 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">自己紹介</label>
                <textarea name="bio" rows="4"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="自己紹介を入力...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 送信ボタン -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-orange-500 text-white py-3 rounded-md hover:bg-orange-600 transition font-medium">
                    更新する
                </button>
                <a href="{{ route('mypage') }}" class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition text-center">
                    キャンセル
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
