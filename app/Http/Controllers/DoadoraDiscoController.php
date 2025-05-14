<?php

namespace App\Http\Controllers;

use App\Models\DoadoraDisco;
use Illuminate\Http\Request;

class DoadoraDiscoController extends Controller
{
    private $doadoraDisco;

    public function __construct()
    {
        $this->doadoraDisco = new DoadoraDisco;
    }

    public function pegarTodos(Request $request)
    {
        $inputs = $request->all();
        $usuario_id = isset($inputs["usuario_id"]) ? $inputs["usuario_id"] : NULL;
        $filtros = isset($inputs["filtros"]) ? $inputs["filtros"] : NULL;

        $doadoras = $this->doadoraDisco->pegarTodos($usuario_id, $filtros);

        return response()->json($doadoras);
    }

    public function pegarTodosSelect(Request $request)
    {
        $inputs = $request->all();
        $usuario_id = isset($inputs["usuario_id"]) ? $inputs["usuario_id"] : NULL;
        $especie_id = isset($inputs["especie_id"]) ? $inputs["especie_id"] : NULL;

        $doadoras = $this->doadoraDisco->pegarTodosSelect($usuario_id, $especie_id);

        return response()->json($doadoras);
    }

    public function pegarTipoDoacao()
    {
        $doadoras = $this->doadoraDisco->pegarTipoDoacao();

        return response()->json($doadoras);
    }

    public function pegarTipoDivisao()
    {
        $doadoras = $this->doadoraDisco->pegarTipoDivisao();

        return response()->json($doadoras);
    }

    public function pegarPorId(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : NULL;

        $doadoras = $this->doadoraDisco->pegarPorId($id);

        return response()->json($doadoras);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            "colmeia_id" => "required",
            "tipo_doacao_id" => "required",
        ]);

        $inputs = $request->all();

        $existe = $this->doadoraDisco->existeDoadora($inputs);

        if ($existe) {
            return response()->json(["erro" => TRUE, "msg" => "Colmeia já é doadora"]);
        }

        $cadastrar = $this->doadoraDisco->cadastrar($inputs);

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

        $existe = $this->doadoraDisco->existeDoadora($inputs);

        if ($existe) {
            return response()->json(["erro" => TRUE, "msg" => "Colmeia já é doadora"]);
        }

        $editar = $this->doadoraDisco->editar($inputs);

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

        $excluir = $this->doadoraDisco->excluir($id);

        if ($excluir->erro) {
            return response()->json(["erro" => TRUE, "msg" => $excluir->msg]);
        }

        return response()->json(["msg" => "Colmeia excluida com sucesso!"]);
    }
}