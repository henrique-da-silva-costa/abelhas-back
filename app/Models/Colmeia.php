<?php

namespace App\Models;

use App\Http\Controllers\Tabela;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Colmeia extends Model
{
    private $tabela;

    public function __construct()
    {
        $this->tabela = Tabela::COLMEIA;
    }

    public function pegarTodos()
    {
        try {
            $dados = DB::table($this->tabela)->get();

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarColmeiasMatrizes($usuario_id)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }

            $dados = DB::table($this->tabela)
                ->where("status_id", "=", 2)
                ->where("usuario_id", "=", $usuario_id)
                ->get();

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarColmeiaMatriz($id)
    {
        try {
            if (!is_numeric($id)) {
                return NULL;
            }

            $dados = DB::table($this->tabela)
                ->where("id", "=", $id)
                ->where("status_id", "=", 2)
                ->first();

            return $dados;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function pegarPorId($id)
    {
        try {
            $dados = DB::table($this->tabela)->where("id", "=", $id)->first();
            return $dados;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function pegarPorUsuarioId($usuario_id)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }

            $dados = DB::table($this->tabela)->where("usuario_id", "=", $usuario_id)->orderBy("id", "desc")->paginate(4);

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function cadastrar($dados)
    {
        try {
            $retorno = new stdClass;
            $retorno->msg = NULL;
            $retorno->erro = FALSE;

            $nome = isset($dados["nome"]) ? $dados["nome"] : NULL;
            $data_criacao = isset($dados["data_criacao"]) ? $dados["data_criacao"] : NULL;
            $data_alteracao = isset($dados["data_alteracao"]) ? $dados["data_alteracao"] : NULL;
            $data_divisao = isset($dados["data_divisao"]) ? $dados["data_divisao"] : NULL;
            $genero_id = isset($dados["genero_id"]) ? $dados["genero_id"] : NULL;
            $especie_id = isset($dados["especie_id"]) ? $dados["especie_id"] : NULL;
            $status_id = isset($dados["status_id"]) ? $dados["status_id"] : NULL;
            $doadora_id = isset($dados["doadora_id"]) ? $dados["doadora_id"] : NULL;
            $tipo_divisao_id = isset($dados["tipo_divisao_id"]) ? $dados["tipo_divisao_id"] : NULL;
            $doadora_id2 = isset($dados["doadora_id2"]) ? $dados["doadora_id2"] : NULL;
            $usuario_id = isset($dados["usuario_id"]) ? $dados["usuario_id"] : NULL;

            DB::table($this->tabela)->insert([
                "nome" => $nome,
                "data_criacao" => $data_criacao,
                "data_alteracao" => $data_alteracao,
                "data_divisao" => $data_divisao,
                "genero_id" => $genero_id,
                "especie_id" => $especie_id,
                "status_id" => $status_id,
                "doadora_id" => $doadora_id,
                "tipo_divisao_id" => $tipo_divisao_id,
                "doadora_id2" => $doadora_id2,
                "usuario_id" => $usuario_id,
            ]);

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->msg = $th->getMessage();
            $retorno->erro = TRUE;

            return $retorno;
        }
    }

    public function editar($dados)
    {
        try {
            $retorno = new stdClass;
            $retorno->msg = NULL;
            $retorno->erro = FALSE;

            $id = isset($dados["id"]) ? $dados["id"] : NULL;
            $nome = isset($dados["nome"]) ? $dados["nome"] : NULL;
            $data_criacao = isset($dados["data_criacao"]) ? $dados["data_criacao"] : NULL;
            $data_alteracao = isset($dados["data_alteracao"]) ? $dados["data_alteracao"] : NULL;
            $data_divisao = isset($dados["data_divisao"]) ? $dados["data_divisao"] : NULL;
            $genero_id = isset($dados["genero_id"]) ? $dados["genero_id"] : NULL;
            $especie_id = isset($dados["especie_id"]) ? $dados["especie_id"] : NULL;
            $status_id = isset($dados["status_id"]) ? $dados["status_id"] : NULL;
            $doadora_id = isset($dados["doadora_id"]) ? $dados["doadora_id"] : NULL;
            $tipo_divisao_id = isset($dados["tipo_divisao_id"]) ? $dados["tipo_divisao_id"] : NULL;
            $doadora_id2 = isset($dados["doadora_id2"]) ? $dados["doadora_id2"] : NULL;
            $usuario_id = isset($dados["usuario_id"]) ? $dados["usuario_id"] : NULL;

            DB::table($this->tabela)->where("id", "=", $id)->update([
                "nome" => $nome,
                "data_criacao" => $data_criacao,
                "data_alteracao" => $data_alteracao,
                "data_divisao" => $data_divisao,
                "genero_id" => $genero_id,
                "especie_id" => $especie_id,
                "status_id" => $status_id,
                "doadora_id" => $doadora_id,
                "tipo_divisao_id" => $tipo_divisao_id,
                "doadora_id2" => $doadora_id2,
                "usuario_id" => $usuario_id,
            ]);

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->msg = $th->getMessage();
            $retorno->erro = TRUE;

            return $retorno;
        }
    }

    public function excluir($dados)
    {
        try {
            $retorno = new stdClass;
            $retorno->msg = NULL;
            $retorno->erro = FALSE;

            $id = isset($dados["id"]) ? $dados["id"] : NULL;

            DB::table($this->tabela)->where("id", "=", $id)->delete();

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->msg = $th->getMessage();
            $retorno->erro = TRUE;

            return $retorno;
        }
    }
}