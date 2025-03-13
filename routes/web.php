<?php

use App\Http\Controllers\ColmeiaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// Token
Route::get('/token', function () {
    return response()->json(["token" => csrf_token()]);
});
// Token

//usuario
Route::post('/usuario/cadastrar', [UsuarioController::class, "cadastrar"])->name("UsuarioController.cadastrar");
//usuario

// Colmeia
Route::get('/', [ColmeiaController::class, "pegarTodos"])->name("ColmeiaController.pegarTodos");
Route::post('/colmeia/cadastrar', [ColmeiaController::class, "cadastrar"])->name("ColmeiaController.cadastrar");
// Colmeia