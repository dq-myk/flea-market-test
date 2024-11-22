<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_name' => 'required',
            'item_detail' => 'required | max:255',
            'item_image' => 'required | mimes:jpeg,png',
            'category_ids' => 'required | array',  // 修正: category_ids に対応
            'category_ids.*' => 'exists:categories,id',  // 配列内の各category_idがcategoriesテーブルに存在することを確認
            'condition' => 'required|in:良好,目立った傷や汚れなし,やや傷や汚れあり,状態が悪い',
            'price' => 'required | numeric | min:0',
        ];
    }
}
