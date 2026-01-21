<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

// 公開ページ
Route::get('/', [RecipeController::class, 'welcome'])->name('welcome');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

// 認証必須ページ
Route::middleware('auth')->group(function () {
    // レシピ投稿（/recipes/createは/recipes/{recipe}より前に定義する必要がある）
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // お気に入り
    Route::post('/favorites/{recipe}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{recipe}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // レビュー
    Route::post('/recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // マイページ
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// レシピ詳細（認証不要、createより後に定義）
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

require __DIR__.'/auth.php';
