<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Review;
use App\Http\Requests\ReviewStoreRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(ReviewStoreRequest $request, Recipe $recipe)
    {
        $exists = Review::where('user_id', auth()->id())
            ->where('recipe_id', $recipe->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'すでにレビューを投稿しています');
        }

        Review::create([
            'user_id' => auth()->id(),
            'recipe_id' => $recipe->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'レビューを投稿しました');
    }

    public function update(ReviewStoreRequest $request, Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'レビューを更新しました');
    }

    public function destroy(Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'レビューを削除しました');
    }
}
