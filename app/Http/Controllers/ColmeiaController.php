<?php

namespace App\Http\Controllers;

use App\Models\Colmeia;
use App\Models\DoadoraCampeira;
use App\Models\DoadoraDisco;
use App\Models\Especie;
use App\Models\Genero;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ColmeiaController extends Controller
{
    private $colmeia;
    private $genero;
    private $especie;
    private $status;
    private $doadoraDisco;
    private $doadoraCampeira;

    public function __construct()
    {
        $this->colmeia = new Colmeia;
        $this->genero = new Genero;
        $this->especie = new Especie;
        $this->status = new Status;
        $this->doadoraDisco = new DoadoraDisco;
        $this->doadoraCampeira = new DoadoraCampeira;
    }

    public function pegarTodos()
    {
        $colmeias = $this->colmeia->pegarTodos();

        return response()->json($colmeias);
    }

    public function pegarTipoDivisao()
    {
        $tipoDivisao = $this->colmeia->pegarTipoDivisao();

        return response()->json($tipoDivisao);
    }

    public function pegarColmeiasDivisoes(Request $request)
    {
        $inputs = $request->all();
        $usuario_id = isset($inputs["usuario_id"]) ? $inputs["usuario_id"] : NULL;
        $filtros = isset($inputs["filtros"]) ? $inputs["filtros"] : NULL;

        $colmeias = $this->colmeia->pegarColmeiasDivisoes($usuario_id, $filtros);

        return response()->json($colmeias);
    }

    public function pegarColmeiasMatrizes(Request $request)
    {
        $usuario_id = isset($request["usuario_id"]) ? $request["usuario_id"] : NULL;

        $colmeias = $this->colmeia->pegarColmeiasMatrizes($usuario_id);

        return response()->json($colmeias);
    }

    public function pegarColmeiasMatrizesPaginacao(Request $request)
    {
        $usuario_id = isset($request["usuario_id"]) ? $request["usuario_id"] : NULL;

        $colmeias = $this->colmeia->pegarColmeiasMatrizesPaginacao($usuario_id);

        return response()->json($colmeias);
    }

    public function pegarGeneros()
    {
        $generos = $this->genero->pegarTodos();

        return response()->json($generos);
    }

    public function pegarEspecies()
    {
        $especies = $this->especie->pegarTodos();

        return response()->json($especies);
    }

    public function pegarStatus()
    {
        $status = $this->status->pegarTodos();

        return response()->json($status);
    }

    public function pegarPorId(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : NULL;

        $colmeia = $this->colmeia->pegarPorId($id);

        return response()->json($colmeia);
    }

    public function pegarPorIdImg(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : NULL;

        $colmeia = $this->colmeia->pegarPorIdImg($id);

        return response()->json($colmeia);
    }

    public function pegarEspeciePorId(Request $request)
    {
        $id = isset($request["id"]) ? $request["id"] : NULL;

        $especie = $this->especie->pegarPorId($id);

        return response()->json($especie);
    }

    public function pegarPorUsuarioId(Request $request)
    {
        $inputs = $request->all();
        $usuario_id = isset($inputs["usuario_id"]) ? $inputs["usuario_id"] : NULL;
        $filtros = isset($inputs["filtros"]) ? $inputs["filtros"] : NULL;

        $colmeias = $this->colmeia->pegarPorUsuarioId($usuario_id, $filtros);

        return response()->json($colmeias);
    }

    public function pegarPorGeneroId(Request $request)
    {
        $genero_id = isset($request["genero_id"]) ? $request["genero_id"] : NULL;

        $especies = $this->especie->pegarPorGeneroId($genero_id);

        return response()->json($especies);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            "nome" => "required",
            "genero_id" => "required",
            "especie_id" => "required",
            "status_id" => "required",
            "usuario_id" => "required",
            "img" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $inputs = $request->all();

        $status_id = isset($inputs["status_id"]) ? $inputs["status_id"] : NULL;
        $doadora_disco_id = isset($inputs["doadora_disco_id"]) ? $inputs["doadora_disco_id"] : NULL;
        $doadora_campeira_id = isset($inputs["doadora_campeira_id"]) ? $inputs["doadora_campeira_id"] : NULL;

        $existeDoadoraDisco = $this->colmeia->pegarPorDodoraDiscoId($doadora_campeira_id);
        $existeDoadoraCampeira = $this->colmeia->pegarPorDodoraCampeiraId($doadora_campeira_id);


        $dataDoadoraDisco = $existeDoadoraDisco ? Carbon::createFromFormat("Y-m-d", $existeDoadoraDisco->data_criacao) : NULL;
        $dataDoadoraCampeira = $existeDoadoraCampeira ? Carbon::createFromFormat("Y-m-d", $existeDoadoraCampeira->data_criacao) : NULL;
        $hoje = Carbon::now();

        if ($hoje->diffInDays($dataDoadoraCampeira) < 30 || $hoje->diffInDays($dataDoadoraDisco) < 30) {
            if ($existeDoadoraDisco) {
                return response()->json(["erro" => TRUE, "campo" => "doadora_disco_id", "msg" => "Esse colmeia já esta sendo usada"]);
            }
            if ($existeDoadoraCampeira) {
                return response()->json(["erro" => TRUE, "campo" => "doadora_campeira_id", "msg" => "Esse colmeia já esta sendo usada"]);
            }
        }


        $existeDoadoraCampeira = $this->colmeia->pegarPorDodoraId($doadora_disco_id);

        $imgCaminho = $request->file('img')->store('imagens', 'public');

        $inputs["img"] = "http://" . $_SERVER["HTTP_HOST"] . "/" . "storage" . "/" . $imgCaminho;
        $inputs["img_caminho"] = $imgCaminho;

        $cadastrar = $this->colmeia->cadastrar($inputs);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        if ($status_id == 2) {
            $cadastrarParaDoarDisco = $this->doadoraDisco->cadastrar($cadastrar->id);
            if ($cadastrarParaDoarDisco->erro) {
                return response()->json(["erro" => TRUE, "msg" => $cadastrarParaDoarDisco->msg]);
            }

            $cadastrarParaDoarCampeira = $this->doadoraCampeira->cadastrar($cadastrar->id);
            if ($cadastrarParaDoarCampeira->erro) {
                return response()->json(["erro" => TRUE, "msg" => $cadastrarParaDoarCampeira->msg]);
            }
        }

        return response()->json(["msg" => "Colmeia cadastrada com sucesso!"]);
    }

    public function editar(Request $request)
    {

        $request->validate([
            "nome" => "required",
            // "data_criacao" => "required",
            "genero_id" => "required",
            "especie_id" => "required",
            "status_id" => "required",
            "usuario_id" => "required",
            // "img" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);



        $inputs = $request->all();

        print_r($inputs["data_cricao"]);

        // $imgCaminho = $request->file('img')->store('imagens', 'public');

        // $inputs["img"] = "http://" . $_SERVER["HTTP_HOST"] . "/" . "storage" . "/" . $imgCaminho;
        // $inputs["img_caminho"] = $imgCaminho;

        $id = isset($inputs["id"]) ? $inputs["id"] : NULL;
        $status_id = isset($inputs["status_id"]) ? $inputs["status_id"] : NULL;

        $colmeia = $this->colmeia->pegarPorId($id);

        // $data_criacao = Carbon::create($colmeia->data_criacao);

        $hoje = Carbon::now();

        // $diferenca = $data_criacao->diffInDays($hoje);

        $colmeiaMatriz = $this->colmeia->pegarColmeiaMatriz($id);

        if ($colmeiaMatriz && $status_id == 1) {
            return response()->json(["erro" => TRUE, "campo" => "status_id", "msg" => "Colmeia matriz não pode ser divisão"]);
        }

        // if (!$colmeiaMatriz) {
        //     if ($status_id == 2 && $diferenca < 15) {
        //         return response()->json(["erro" => TRUE, "campo" => "status_id", "msg" => "Para se tornar matriz deve ter pelomenos 15 dias"]);
        //     }
        // }

        $editar = $this->colmeia->editar($inputs);

        if ($editar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $editar->msg]);
        }

        return response()->json(["msg" => "Colmeia editada com sucesso!"]);
    }

    public function editarImg(Request $request)
    {
        $request->validate([
            "id" => "required",
            // "img" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $inputs = $request->all();


        print_r($_FILES);

        $imgCaminho = $request->file('img')->store('imagens', 'public');

        $inputs["img"] = "http://" . $_SERVER["HTTP_HOST"] . "/" . "storage" . "/" . $imgCaminho;
        $inputs["img_caminho"] = $imgCaminho;

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
