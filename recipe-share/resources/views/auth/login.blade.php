@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ログイン</h1>
            <p class="mt-2 text-gray-600">アカウントにログインしてください</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="bg-white rounded-lg shadow p-8 space-y-6">
            @csrf

            <!-- メールアドレス -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus
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

            <!-- ログイン状態を保持 -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="remember"
                           class="text-orange-500 focus:ring-orange-500 rounded">
                    <span class="ml-2 text-sm text-gray-700">ログイン状態を保持する</span>
                </label>
            </div>

            <!-- ログインボタン -->
            <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-md hover:bg-orange-600 transition font-medium">
                ログイン
            </button>

            <!-- 新規登録リンク -->
            <p class="text-center text-sm text-gray-600">
                アカウントをお持ちでない方は
                <a href="{{ route('register') }}" class="text-orange-500 hover:underline">新規登録</a>
            </p>
        </form>
    </div>
</div>
@endsection
