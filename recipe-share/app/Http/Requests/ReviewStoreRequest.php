<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:300',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価を選択してください',
            'rating.min' => '評価は1以上を選択してください',
            'rating.max' => '評価は5以下を選択してください',
            'comment.max' => 'コメントは300文字以内で入力してください',
        ];
    }
}
