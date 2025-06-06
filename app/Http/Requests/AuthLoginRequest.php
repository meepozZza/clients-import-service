<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => 'required|string|exists:users,email',
            'password' => 'required|string',
        ];
    }
}
