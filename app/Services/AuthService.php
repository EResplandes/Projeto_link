<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use DateTime;


class AuthService
{

    public function login($request)
    {

        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = $request->all(['email', 'password']);

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $token = JWTAuth::attempt($credentials);

            // Retornar o token JWT
            return response()->json(['token' => $token]);
        }
    }

    public function logout($request)
    {

        $token = $request->input('token'); // Armazenando token

        $query = auth('api')->logout($token); // Colocando token na blacklist

        return $query;
    }
}
