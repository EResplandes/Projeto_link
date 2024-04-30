<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

Route::get('/user', function (Request $request) {
    return 'teste';
=======
use App\Http\Controllers\AuthController;

Route::prefix("/autenticacao")->group(function () {
    Route::controller(AuthController::class)->group(function (){
        Route::post('/login', 'login');
        Route::post('/logout','logout');
    });
>>>>>>> configuracao_jwt
});
