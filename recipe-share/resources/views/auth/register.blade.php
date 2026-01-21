@extends('layouts.app')

@section('title', '新規登録')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">新規登録</h1>
            <p class="mt-2 text-gray-600">RecipeShareに登録して、レシピを共有しましょう</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="bg-white rounded-lg shadow p-8 space-y-6">
            @csrf

            <!-- ユーザー名 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name') }}" autofocus
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="ユーザー名">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- メールアドレス -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="example@email.com">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">パスワード</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="パスワード">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード確認 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">パスワード（確認）</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="パスワード（確認）">
            </div>

            <!-- 登録ボタン -->
            <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-md hover:bg-orange-600 transition font-medium">
                登録する
            </button>

            <!-- ログインリンク -->
            <p class="text-center text-sm text-gray-600">
                すでにアカウントをお持ちの方は
                <a href="{{ route('login') }}" class="text-orange-500 hover:underline">ログイン</a>
            </p>
        </form>
    </div>
</div>
@endsection
