<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function teste()
    {
        dd('teste');
    }
    public function login(Request $request)
    {

        $query = $this->authService->login($request); // Consulta para realizar autenticação
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function logout(Request $request)
    {

        $query = $this->authService->logout($request); // Consulta para realizar invalidação do token
        return response()->json(['Response' => 'Usuário deslogado com sucesso!']); // Retornando resposta

    }


}
