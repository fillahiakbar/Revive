<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Fortify;
use App\Rules\Turnstile;

class CustomLoginRequest extends FortifyLoginRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            Fortify::username() => 'required|string',
            'password' => 'required|string',
            'cf-turnstile-response' => ['required', new Turnstile],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cf-turnstile-response.required' => 'يرجى إكمال التحقق من رمز CAPTCHA',
        ];
    }
}
