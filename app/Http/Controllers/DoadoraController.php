<?php

namespace App\Http\Controllers;

use App\Models\Doadora;
use Illuminate\Http\Request;

class DoadoraController extends Controller
{

    private $doadora;

    public function __construct()
    {
        $this->doadora = new Doadora;
    }

    public function pegarTodos()
    {
        $doadoras = $this->doadora->pegarTodos();

        return response()->json($doadoras);
    }

    public function pegarTipoDoacao()
    {
        $doadoras = $this->doadora->pegarTipoDoacao();

        return response()->json($doadoras);
    }

    public function pegarPorId(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : NULL;

        $doadoras = $this->doadora->pegarPorId($id);

        return response()->json($doadoras);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            "colmeia_id" => "required",
            "tipo_doacao_id" => "required",
        ]);

        $inputs = $request->all();

        $cadastrar = $this->doadora->cadastrar($inputs);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        return response()->json(["msg" => "Colmeia cadastrada com sucesso!"]);
    }

    public function editar(Request $request)
    {
        $request->validate([
            "colmeia_id" => "required",
            "tipo_doacao_id" => "required",
        ]);

        $inputs = $request->all();

        $editar = $this->doadora->editar($inputs);

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

        $excluir = $this->doadora->excluir($id);

        if ($excluir->erro) {
            return response()->json(["erro" => TRUE, "msg" => $excluir->msg]);
        }

        return response()->json(["msg" => "Colmeia excluida com sucesso!"]);
    }
}