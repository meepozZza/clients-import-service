<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController
{
    public function login(AuthLoginRequest $request)
    {
        $user = User::whereRaw('lower(email)=?', [$request->validated('login')])->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials are incorrect.',
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
        ])->withCookie(new Cookie('accessToken', $token));
    }
}
