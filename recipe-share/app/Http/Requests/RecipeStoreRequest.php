<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'cooking_time' => 'required|integer|min:1|max:999',
            'servings' => 'required|integer|min:1|max:10',
            'difficulty' => 'required|in:easy,medium,hard',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'sub_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:100',
            'ingredients.*.quantity' => 'required|string|max:50',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'is_public' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'レシピ名を入力してください',
            'title.max' => 'レシピ名は100文字以内で入力してください',
            'description.required' => '説明を入力してください',
            'description.max' => '説明は500文字以内で入力してください',
            'category_id.required' => 'カテゴリーを選択してください',
            'cooking_time.required' => '調理時間を入力してください',
            'cooking_time.min' => '調理時間は1分以上で入力してください',
            'cooking_time.max' => '調理時間は999分以内で入力してください',
            'servings.required' => '人数を入力してください',
            'servings.min' => '人数は1人以上で入力してください',
            'servings.max' => '人数は10人以内で入力してください',
            'difficulty.required' => '難易度を選択してください',
            'main_image.required' => 'メイン画像をアップロードしてください',
            'main_image.image' => 'メイン画像は画像ファイルをアップロードしてください',
            'main_image.mimes' => 'メイン画像はJPEG、PNG形式のみアップロードできます',
            'main_image.max' => 'メイン画像は2MB以内でアップロードしてください',
            'ingredients.required' => '材料を1つ以上入力してください',
            'ingredients.*.name.required' => '材料名を入力してください',
            'ingredients.*.quantity.required' => '分量を入力してください',
            'steps.required' => '作り方を1つ以上入力してください',
            'steps.*.description.required' => '作り方を入力してください',
        ];
    }
}
