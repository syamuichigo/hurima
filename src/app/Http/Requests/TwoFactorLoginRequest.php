<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest as FortifyTwoFactorLoginRequest;

class TwoFactorLoginRequest extends FortifyTwoFactorLoginRequest
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
            'code' => 'nullable|string',
            'recovery_code' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'code.string' => '認証コードは文字列で入力してください',
            'recovery_code.string' => 'リカバリーコードは文字列で入力してください',
        ];
    }
}

