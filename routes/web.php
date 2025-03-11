<?php

use App\Http\Controllers\ColmeiaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ColmeiaController::class, "pegarTodos"])->name("ColmeiaController.pegarTodos");
Route::post('/cadastrar', [ColmeiaController::class, "cadastrar"])->name("ColmeiaController.cadastrar");