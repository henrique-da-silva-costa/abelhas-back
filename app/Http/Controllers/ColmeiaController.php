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

    public function pegarPorId(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : NULL;

        $colmeias = $this->colmeia->pegarPorId($id);

        return response()->json($colmeias);
    }

    public function pegarPorUsuarioId(Request $request)
    {
        $usuario_id = isset($request["usuario_id"]) ? $request["usuario_id"] : NULL;

        $colmeias = $this->colmeia->pegarPorUsuarioId($usuario_id);

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
            "usuario_id" => "required",
        ]);

        $inputs = $request->all();

        $cadastrar = $this->colmeia->cadastrar($inputs);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        return response()->json(["msg" => "Colmeia cadastrada com sucesso!"]);
    }

    public function editar(Request $request)
    {
        $request->validate([
            "nome" => "required",
            "data_criacao" => "required",
            "genero_id" => "required",
            "especie_id" => "required",
            "status_id" => "required",
            "usuario_id" => "required",
        ]);

        $inputs = $request->all();

        $editar = $this->colmeia->editar($inputs);

        if ($editar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $editar->msg]);
        }

        return response()->json(["msg" => "Colmeia editada com sucesso!"]);
    }

    public function excluir(Request $request)
    {
        $inputs = $request->all();

        $id = isset($inputs["id"]) ? $inputs["id"] : NULL;

        if (!is_numeric($id)) {
            return response()->json(["erro" => TRUE, "msg" => "Colmeia não encontrada"]);
        }

        $excluir = $this->colmeia->excluir($inputs);

        if ($excluir->erro) {
            return response()->json(["erro" => TRUE, "msg" => $excluir->msg]);
        }

        return response()->json(["msg" => "Colmeia excluida com sucesso!"]);
    }
}
