<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Recipe $recipe)
    {
        $exists = Favorite::where('user_id', auth()->id())
            ->where('recipe_id', $recipe->id)
            ->exists();

        if (!$exists) {
            Favorite::create([
                'user_id' => auth()->id(),
                'recipe_id' => $recipe->id,
            ]);
        }

        if (request()->ajax()) {
            return response()->json([
                'favorited' => true,
                'count' => $recipe->favorites()->count(),
            ]);
        }

        return back()->with('success', 'お気に入りに追加しました');
    }

    public function destroy(Recipe $recipe)
    {
        Favorite::where('user_id', auth()->id())
            ->where('recipe_id', $recipe->id)
            ->delete();

        if (request()->ajax()) {
            return response()->json([
                'favorited' => false,
                'count' => $recipe->favorites()->count(),
            ]);
        }

        return back()->with('success', 'お気に入りを解除しました');
    }
}
