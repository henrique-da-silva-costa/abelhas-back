<?php

use App\Http\Controllers\ColmeiaController;
use App\Http\Controllers\DoadoraCampeiraController;
use App\Http\Controllers\DoadoraController;
use App\Http\Controllers\DoadoraDiscoController;
use App\Http\Controllers\UsuarioController;
use App\Models\Colmeia;
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
Route::post("/recuperarsenha", [UsuarioController::class, "recuperarSenha"])->name("UsuarioController.recuperarSenha");
Route::post("/usuario/cadastrar", [UsuarioController::class, "cadastrar"])->name("UsuarioController.cadastrar");
//usuario

// Colmeia
Route::get("/", [ColmeiaController::class, "pegarTodos"])->name("ColmeiaController.pegarTodos");
Route::get("/colmeias/tipodivisoes", [ColmeiaController::class, "pegarTipoDivisao"])->name("ColmeiaController.pegarTipoDivisao");
Route::get("/colmeias/divisoes", [ColmeiaController::class, "pegarColmeiasDivisoes"])->name("ColmeiaController.pegarColmeiasDivisoes");
Route::get("/colmeias/matrizes", [ColmeiaController::class, "pegarColmeiasMatrizes"])->name("ColmeiaController.pegarColmeiasMatrizes");
Route::get("/colmeias/matrizes/paginacao", [ColmeiaController::class, "pegarColmeiasMatrizesPaginacao"])->name("ColmeiaController.pegarColmeiasMatrizesPaginacao");
Route::get("/generos", [ColmeiaController::class, "pegarGeneros"])->name("ColmeiaController.pegarGeneros");
Route::get("/especies", [ColmeiaController::class, "pegarPorGeneroId"])->name("ColmeiaController.pegarPorGeneroId");
Route::get("/status", [ColmeiaController::class, "pegarStatus"])->name("ColmeiaController.pegarStatus");
Route::get("/colmeias", [ColmeiaController::class, "pegarPorUsuarioId"])->name("ColmeiaController.pegarPorUsuarioId");
Route::get("/colmeia", [ColmeiaController::class, "pegarPorId"])->name("ColmeiaController.pegarPorId");
Route::get("/colmeia/img", [ColmeiaController::class, "pegarPorIdImg"])->name("ColmeiaController.pegarPorIdImg");
Route::post("/colmeia/cadastrar", [ColmeiaController::class, "cadastrar"])->name("ColmeiaController.cadastrar");
Route::put("/colmeia/editar", [ColmeiaController::class, "editar"])->name("ColmeiaController.editar");
Route::put("/colmeia/editar/img", [ColmeiaController::class, "editarImg"])->name("ColmeiaController.editarImg");
Route::options("/colmeia/excluir", [ColmeiaController::class, "excluir"])->name("ColmeiaController.excluir");
// Colmeia

// Doadoras
Route::get("/doadoras/disco", [DoadoraDiscoController::class, "pegarTodos"])->name("DoadoraDiscoController.pegarTodos");
Route::get("/doadoras/disco/select", [DoadoraDiscoController::class, "pegarTodosSelect"])->name("DoadoraDiscoController.pegarTodosSelect");
Route::get("/doadora/disco", [DoadoraDiscoController::class, "pegarPorId"])->name("DoadoraDiscoController.pegarPorId");
Route::get("/doadora/tipodoacao/disco", [DoadoraDiscoController::class, "pegarTipoDoacao"])->name("DoadoraDiscoController.pegarTipoDoacao");
Route::post("/doadora/cadastrar/disco", [DoadoraDiscoController::class, "cadastrar"])->name("DoadoraDiscoController.cadastrar");
Route::put("/doadora/editar/disco", [DoadoraDiscoController::class, "editar"])->name("DoadoraDiscoController.editar");
Route::options("/doadora/excluir/disco", [DoadoraDiscoController::class, "excluir"])->name("DoadoraDiscoController.excluir");

Route::get("/doadoras/campeira", [DoadoraCampeiraController::class, "pegarTodos"])->name("DoadoraCampeiraController.pegarTodos");
Route::get("/doadoras/campeira/select", [DoadoraCampeiraController::class, "pegarTodosSelect"])->name("DoadoraCampeiraController.pegarTodosSelect");
Route::get("/doadora/campeira", [DoadoraCampeiraController::class, "pegarPorId"])->name("DoadoraCampeiraController.pegarPorId");
Route::get("/doadora/tipodoacao/campeira", [DoadoraCampeiraController::class, "pegarTipoDoacao"])->name("DoadoraCampeiraController.pegarTipoDoacao");
Route::post("/doadora/cadastrar/campeira", [DoadoraCampeiraController::class, "cadastrar"])->name("DoadoraCampeiraController.cadastrar");
Route::put("/doadora/editar/campeira", [DoadoraCampeiraController::class, "editar"])->name("DoadoraCampeiraController.editar");
Route::options("/doadora/excluir/campeira", [DoadoraCampeiraController::class, "excluir"])->name("DoadoraCampeiraController.excluir");
// Doadoras