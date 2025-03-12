<?php

namespace App\Http\Controllers;

use App\Models\Colmeia;
use Illuminate\Http\Request;

class ColmeiaController extends Controller
{
    private $colmeia;

    public function __construct()
    {
        $this->colmeia = new Colmeia;
    }

    public function pegarTodos()
    {
        $colmeias = $this->colmeia->pegarTodos();

        return response()->json($colmeias);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            "nome" => "required",
            "data_criacao" => "required",
            "genero_id" => "required",
            "especie_id" => "required",
            "status_id" => "required",
        ]);

        $dados = $request->all();

        $cadastrar = $this->colmeia->cadastrar($dados);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        return response()->json(["msg" => "Colmeia cadastrada com sucesso!"]);
    }
}