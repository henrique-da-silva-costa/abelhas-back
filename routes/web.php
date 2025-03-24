<?php

use App\Http\Controllers\ColmeiaController;
use App\Http\Controllers\DoadoraController;
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
Route::get("/colmeias/matrizes", [ColmeiaController::class, "pegarColmeiasMatrizes"])->name("ColmeiaController.pegarColmeiasMatrizes");
Route::get("/colmeias/matrizes/paginacao", [ColmeiaController::class, "pegarColmeiasMatrizesPaginacao"])->name("ColmeiaController.pegarColmeiasMatrizesPaginacao");
Route::get("/generos", [ColmeiaController::class, "pegarGeneros"])->name("ColmeiaController.pegarGeneros");
Route::get("/especies", [ColmeiaController::class, "pegarPorGeneroId"])->name("ColmeiaController.pegarPorGeneroId");
Route::get("/status", [ColmeiaController::class, "pegarStatus"])->name("ColmeiaController.pegarStatus");
Route::get("/colmeias", [ColmeiaController::class, "pegarPorUsuarioId"])->name("ColmeiaController.pegarPorUsuarioId");
Route::get("/colmeia", [ColmeiaController::class, "pegarPorId"])->name("ColmeiaController.pegarPorId");
Route::post("/colmeia/cadastrar", [ColmeiaController::class, "cadastrar"])->name("ColmeiaController.cadastrar");
Route::put("/colmeia/editar", [ColmeiaController::class, "editar"])->name("ColmeiaController.editar");
Route::options("/colmeia/excluir", [ColmeiaController::class, "excluir"])->name("ColmeiaController.excluir");
// Colmeia

// Doadoras
Route::get("/doadoras", [DoadoraController::class, "pegarTodos"])->name("DoadoraController.pegarTodos");
Route::get("/doadora", [DoadoraController::class, "pegarPorId"])->name("DoadoraController.pegarPorId");
Route::get("/doadora/tipodoacao", [DoadoraController::class, "pegarTipoDoacao"])->name("DoadoraController.pegarTipoDoacao");
Route::post("/doadora/cadastrar", [DoadoraController::class, "cadastrar"])->name("DoadoraController.cadastrar");
Route::put("/doadora/editar", [DoadoraController::class, "editar"])->name("DoadoraController.editar");
Route::options("/doadora/excluir", [DoadoraController::class, "excluir"])->name("DoadoraController.excluir");
// Doadoras