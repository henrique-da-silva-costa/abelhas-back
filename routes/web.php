<?php

use App\Http\Controllers\ColmeiaController;
use App\Http\Middleware\Cors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/token', function () {
    return response()->json(["token" => csrf_token()]);
});
Route::get('/', [ColmeiaController::class, "pegarTodos"])->name("ColmeiaController.pegarTodos");
Route::post('/cadastrar', [ColmeiaController::class, "cadastrar"])->name("ColmeiaController.cadastrar");