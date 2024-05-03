<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateIdParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');

        // Verifica se o ID é um número inteiro e não está vazio
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['error' => 'O parâmetro ID é inválido.'], 400);
        }

        return $next($request);
    }
}
