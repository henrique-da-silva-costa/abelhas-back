<?php

use App\Http\Controllers\ColmeiaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// Token
Route::get("/token", function () {
    return response()->json(["token" => csrf_token()]);
});
// Token

//usuario
Route::post("/login", [UsuarioController::class, "login"])->name("UsuarioController.login");
Route::post("/verificaremail", [UsuarioController::class, "verificarEmail"])->name("UsuarioController.verificarEmail");
Route::get("/verificaremailapp", [UsuarioController::class, "verificarEmailApp"])->name("UsuarioController.verificarEmailApp");
Route::post("/recuperarsenha", [UsuarioController::class, "recuperarSenhaApp"])->name("UsuarioController.recuperarSenhaApp");
Route::post("/usuario/cadastrar", [UsuarioController::class, "cadastrar"])->name("UsuarioController.cadastrar");
//usuario

// Colmeia
Route::get("/", [ColmeiaController::class, "pegarTodos"])->name("ColmeiaController.pegarTodos");
Route::get("/colmeias", [ColmeiaController::class, "pegarPorUsuarioId"])->name("ColmeiaController.pegarPorUsuarioId");
Route::get("/colmeia", [ColmeiaController::class, "pegarPorId"])->name("ColmeiaController.pegarPorId");
Route::post("/colmeia/cadastrar", [ColmeiaController::class, "cadastrar"])->name("ColmeiaController.cadastrar");
Route::put("/colmeia/editar", [ColmeiaController::class, "editar"])->name("ColmeiaController.editar");
Route::options("/colmeia/excluir", [ColmeiaController::class, "excluir"])->name("ColmeiaController.excluir");
// Colmeia