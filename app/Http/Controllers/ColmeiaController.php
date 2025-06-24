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
    private $hoje;

    public function __construct()
    {
        $this->colmeia = new Colmeia;
        $this->genero = new Genero;
        $this->especie = new Especie;
        $this->status = new Status;
        $this->doadoraDisco = new DoadoraDisco;
        $this->doadoraCampeira = new DoadoraCampeira;
        $this->hoje = Carbon::now();
    }

    public function transformarDivisaoEmMatriz()
    {
        $colmeias = $this->colmeia->pegarTodasDivisoesParaVerificarData();

        foreach ($colmeias as $colmeia) {
            if (Carbon::now()->diffInMonths(Carbon::createFromFormat("Y-m-d", $colmeia->data_criacao)) >= 6) {
                $this->colmeia->editarStatus($colmeia->id);
            }
        }
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

        if ($doadora_disco_id) {
            $existeDoadoraDisco = $this->colmeia->pegarPorDodoraDiscoId($doadora_disco_id);
        }

        if ($doadora_campeira_id) {
            $existeDoadoraCampeira = $this->colmeia->pegarPorDodoraCampeiraId($doadora_campeira_id);
        }

        $dataDoadoraDisco = isset($existeDoadoraDisco->data_doacao) ? Carbon::createFromFormat("Y-m-d", $existeDoadoraDisco->data_doacao) : NULL;
        $dataDoadoraCampeira = isset($existeDoadoraCampeira->data_doacao) ? Carbon::createFromFormat("Y-m-d", $existeDoadoraCampeira->data_doacao) : NULL;



        // $dataEspecialTeste = Carbon::createFromFormat("Y-m-d", "2025-08-15");

        if ($doadora_campeira_id > 0 || $doadora_disco_id > 0) {
            if ($dataDoadoraCampeira || $dataDoadoraDisco) {
                if ($dataDoadoraCampeira->diffInDays(Carbon::now()) < 60 || $dataDoadoraDisco->diffInDays(Carbon::now()) < 60) {
                    if ($existeDoadoraDisco) {
                        return response()->json(["erro" => TRUE, "campo" => "doadora_disco_id", "msg" => "Esse colmeia já esta sendo usada"]);
                    }

                    if ($existeDoadoraCampeira) {
                        return response()->json(["erro" => TRUE, "campo" => "doadora_campeira_id", "msg" => "Esse colmeia já esta sendo usada"]);
                    }
                }
            }
        }

        // $existeDoadoraCampeira = $this->colmeia->pegarPorDodoraId($doadora_disco_id);

        $imgCaminho = $request->file("img") ? $request->file('img')->store('imagens', 'public') : NULL;

        $inputs["img"] = $request->file("img") ? "http://" . $_SERVER["HTTP_HOST"] . "/" . "storage" . "/" . $imgCaminho : NULL;
        $inputs["img_caminho"] = $imgCaminho;

        $cadastrar = $this->colmeia->cadastrar($inputs);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        if ($status_id == 1) {
            if ($doadora_campeira_id > 0 || $doadora_disco_id > 0) {
                $colmeia = $this->colmeia->pegarPorId($cadastrar->id);

                $editarDoadoraCampeira = $this->doadoraCampeira->editar($doadora_campeira_id,  $colmeia->data_criacao);
                if ($editarDoadoraCampeira->erro) {
                    return response()->json(["erro" => TRUE, "msg" => $editarDoadoraCampeira->msg]);
                }

                $editarDoadoraDisco = $this->doadoraDisco->editar($doadora_disco_id, $colmeia->data_criacao);
                if ($editarDoadoraDisco->erro) {
                    return response()->json(["erro" => TRUE, "msg" => $editarDoadoraDisco->msg]);
                }
            }
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
            "data_criacao" => "required",
            "genero_id" => "required",
            "especie_id" => "required",
            "status_id" => "required",
            "usuario_id" => "required",
            // "img" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $inputs = $request->all();


        $dataCriacao = isset($inputs["data_criacao"]) ? $inputs["data_criacao"] : NULL;
        $status = isset($inputs["status"]) ? $inputs["status"] : NULL;

        $dataCriacao = Carbon::createFromFormat("Y-m-d", $dataCriacao);

        if ($this->hoje->diffInMonths($dataCriacao) < 6 && $status == 1) {
            return response()->json(["erro" => true, "campo" => "data_descricao", "msg" => "A colmeia so pode ser matriz após 6 meses de sua criação"]);
        }

        $id = isset($inputs["id"]) ? $inputs["id"] : NULL;
        $status_id = isset($inputs["status_id"]) ? $inputs["status_id"] : NULL;

        $colmeia = $this->colmeia->pegarPorId($id);

        $colmeiaMatriz = $this->colmeia->pegarColmeiaMatriz($id);

        if ($colmeiaMatriz && $status_id == 1) {
            return response()->json(["erro" => TRUE, "campo" => "status_id", "msg" => "Colmeia matriz não pode ser divisão"]);
        }

        $editar = $this->colmeia->editar($inputs);

        if ($editar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $editar->msg]);
        }

        return response()->json(["msg" => "Colmeia editada com sucesso!"]);
    }

    public function editarImg(Request $request)
    {
        print_r($_FILES["img"]);
        $request->validate([
            "id" => "required",
            // "img" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $inputs = $request->all();



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


        $colemiaDoadoraCampeira = $this->doadoraCampeira->existeColmeia($id);
        $colemiaDoadoraDisco = $this->doadoraDisco->existeColmeia($id);

        if ($colemiaDoadoraCampeira) {
            return response()->json(["erro" => true, "msg" => "Essa colmeia é uma doadora não pode ser excluida"]);
        }

        if ($colemiaDoadoraDisco) {
            return response()->json(["erro" => true, "msg" => "Essa colmeia é uma doadora não pode ser excluida"]);
        }

        $excluir = $this->colmeia->excluir($inputs);

        if ($excluir->erro) {
            return response()->json(["erro" => TRUE, "msg" => $excluir->msg]);
        }

        return response()->json(["msg" => "Colmeia excluida com sucesso!"]);
    }
}