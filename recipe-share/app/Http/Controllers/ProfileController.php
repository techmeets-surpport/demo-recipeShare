<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 統計情報
        $stats = [
            'recipes_count' => $user->recipes()->count(),
            'favorites_received' => $user->recipes()
                ->withCount('favorites')
                ->get()
                ->sum('favorites_count'),
            'average_rating' => $user->recipes()
                ->join('reviews', 'recipes.id', '=', 'reviews.recipe_id')
                ->avg('reviews.rating') ?? 0,
        ];

        // タブに応じてレシピを取得
        $tab = $request->get('tab', 'public');

        switch ($tab) {
            case 'private':
                $recipes = $user->recipes()
                    ->where('is_public', false)
                    ->with(['category'])
                    ->withCount('favorites')
                    ->withAvg('reviews', 'rating')
                    ->latest()
                    ->paginate(12);
                break;
            case 'favorites':
                $recipes = $user->favoriteRecipes()
                    ->public()
                    ->with(['user', 'category'])
                    ->withCount('favorites')
                    ->withAvg('reviews', 'rating')
                    ->latest()
                    ->paginate(12);
                break;
            default:
                $recipes = $user->recipes()
                    ->where('is_public', true)
                    ->with(['category'])
                    ->withCount('favorites')
                    ->withAvg('reviews', 'rating')
                    ->latest()
                    ->paginate(12);
                break;
        }

        return view('mypage.index', compact('user', 'stats', 'recipes', 'tab'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bio' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は100文字以内で入力してください',
            'bio.max' => '自己紹介は500文字以内で入力してください',
            'profile_image.image' => 'プロフィール画像は画像ファイルをアップロードしてください',
            'profile_image.mimes' => 'プロフィール画像はJPEG、PNG形式のみアップロードできます',
            'profile_image.max' => 'プロフィール画像は2MB以内でアップロードしてください',
        ]);

        $user = auth()->user();

        $data = [
            'name' => $request->name,
            'bio' => $request->bio,
        ];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->profile_image->getClientOriginalExtension();
            $data['profile_image'] = $request->profile_image->storeAs('profiles', $filename, 'public');
        }

        $user->update($data);

        return redirect()->route('mypage')
            ->with('success', 'プロフィールを更新しました');
    }
}
