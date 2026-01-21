<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Tag;
use App\Services\RecipeService;
use App\Http\Requests\RecipeStoreRequest;
use App\Http\Requests\RecipeUpdateRequest;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    protected $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    public function welcome()
    {
        $newRecipes = Recipe::public()
            ->with(['user', 'category'])
            ->withCount('favorites')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(6)
            ->get();

        $popularRecipes = Recipe::public()
            ->with(['user', 'category'])
            ->withCount('favorites')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('favorites_count')
            ->take(6)
            ->get();

        return view('welcome', compact('newRecipes', 'popularRecipes'));
    }

    public function index(Request $request)
    {
        $query = Recipe::public()
            ->with(['user', 'category', 'tags'])
            ->withCount('favorites')
            ->withAvg('reviews', 'rating');

        // キーワード検索
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhereHas('ingredients', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        // カテゴリーフィルター
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // タグフィルター
        if ($request->filled('tags')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.id', $request->tags);
            });
        }

        // 調理時間フィルター
        if ($request->filled('cooking_time')) {
            switch ($request->cooking_time) {
                case '15':
                    $query->where('cooking_time', '<=', 15);
                    break;
                case '30':
                    $query->where('cooking_time', '<=', 30);
                    break;
                case '60':
                    $query->where('cooking_time', '<=', 60);
                    break;
                case '60+':
                    $query->where('cooking_time', '>', 60);
                    break;
            }
        }

        // 難易度フィルター
        if ($request->filled('difficulty')) {
            $query->whereIn('difficulty', $request->difficulty);
        }

        // ソート
        switch ($request->sort) {
            case 'popular':
                $query->orderByDesc('favorites_count');
                break;
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            default:
                $query->latest();
                break;
        }

        $recipes = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $tags = Tag::all();

        return view('recipes.index', compact('recipes', 'categories', 'tags'));
    }

    public function show(Recipe $recipe)
    {
        if (!$recipe->is_public && (!auth()->check() || auth()->id() !== $recipe->user_id)) {
            abort(404);
        }

        $recipe->load(['user', 'category', 'tags', 'ingredients', 'steps', 'images', 'reviews.user']);
        $recipe->loadCount('favorites');
        $recipe->loadAvg('reviews', 'rating');

        $isFavorited = auth()->check()
            ? $recipe->favorites()->where('user_id', auth()->id())->exists()
            : false;

        $userReview = auth()->check()
            ? $recipe->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('recipes.show', compact('recipe', 'isFavorited', 'userReview'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('recipes.create', compact('categories', 'tags'));
    }

    public function store(RecipeStoreRequest $request)
    {
        $recipe = $this->recipeService->createRecipe($request->validated());

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'レシピを投稿しました');
    }

    public function edit(Recipe $recipe)
    {
        if (auth()->id() !== $recipe->user_id) {
            abort(403);
        }

        $recipe->load(['ingredients', 'steps', 'images', 'tags']);
        $categories = Category::all();
        $tags = Tag::all();

        return view('recipes.edit', compact('recipe', 'categories', 'tags'));
    }

    public function update(RecipeUpdateRequest $request, Recipe $recipe)
    {
        $this->recipeService->updateRecipe($recipe, $request->validated());

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'レシピを更新しました');
    }

    public function destroy(Recipe $recipe)
    {
        if (auth()->id() !== $recipe->user_id) {
            abort(403);
        }

        $this->recipeService->deleteRecipe($recipe);

        return redirect()->route('mypage')
            ->with('success', 'レシピを削除しました');
    }
}
