<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
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
            'name' => 'required|string',
            'info' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'categories' => 'required',
            'condition_id' => 'required',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.string' => '商品名は文字列で入力してください',
            'info.required' => '商品の詳細を入力してください',
            'info.string' => '商品の詳細は文字列で入力してください',
            'info.max' => '商品の詳細は255文字以内で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '商品画像は画像ファイルを選択してください',
            'image.mimes' => '商品画像はjpeg,png,jpg形式で選択してください',
            'categories.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品状態を選択してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は整数で入力してください',
            'price.min' => '価格は0以上で入力してください',
        ];
    }
}
