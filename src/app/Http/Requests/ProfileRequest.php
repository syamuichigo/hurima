<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            // プロフィール更新時は画像の再アップロードを必須にしない
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'name' => 'required|string|max:20',
            'postcode' => 'required|max:8',
            'address' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '画像を選択してください',
            'image.image' => '画像は画像ファイルを選択してください',
            'image.mimes' => '画像はjpeg,png,jpg形式で選択してください',
            'name.required' => '名前を入力してください',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は20文字以内で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.max' => '郵便番号は8文字以内で入力してください',
            'address.required' => '住所を入力してください',
            'address.string' => '住所は文字列で入力してください',
        ];
    }
}
