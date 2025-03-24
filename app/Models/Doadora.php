<?php

namespace App\Models;

use App\Http\Controllers\Tabela;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Doadora extends Model
{
    private $tabela;
    private $tabelaTipoDoacao;

    public function __construct()
    {
        $this->tabela = Tabela::DOADORA;
        $this->tabelaTipoDoacao = Tabela::TIPO_DOACAO;
    }

    public function pegarTodos()
    {
        try {
            $dados = DB::table($this->tabela)->paginate(3);

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarTipoDoacao()
    {
        try {
            $dados = DB::table($this->tabelaTipoDoacao)->get();

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarPorId($id)
    {
        try {
            if (!is_numeric($id)) {
                return NULL;
            }

            $dados = DB::table($this->tabela)->where("id", "=", $id)->first();
            return $dados;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function existeDoadora($id)
    {
        try {
            if (!is_numeric($id)) {
                return NULL;
            }

            $dados = DB::table($this->tabela)->where("id", "=", $id)->first();
            return $dados;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function cadastrar($dados)
    {
        try {
            $resposta = new stdClass;
            $resposta->erro = FALSE;
            $resposta->msg = NULL;

            $colmeia_id = isset($dados["colmeia_id"]) ? $dados["colmeia_id"] : NULL;
            $tipo_doacao_id = isset($dados["tipo_doacao_id"]) ? $dados["tipo_doacao_id"] : NULL;

            $dados = DB::table($this->tabela)->insert([
                "colmeia_id" => $colmeia_id,
                "tipo_doacao_id" => $tipo_doacao_id
            ]);

            return $resposta;
        } catch (\Throwable $th) {
            $resposta = new stdClass;
            $resposta->erro = TRUE;
            $resposta->msg = $th->getMessage();

            return $resposta;
        }
    }
    public function editar($dados)
    {
        try {
            $resposta = new stdClass;
            $resposta->erro = FALSE;
            $resposta->msg = NULL;

            $id = isset($dados["id"]) ? $dados["id"] : NULL;
            $colmeia_id = isset($dados["colmeia_id"]) ? $dados["colmeia_id"] : NULL;
            $tipo_doacao_id = isset($dados["tipo_doacao_id"]) ? $dados["tipo_doacao_id"] : NULL;

            $dados = DB::table($this->tabela)->where("id", "=", $id)->update([
                "colmeia_id" => $colmeia_id,
                "tipo_doacao_id" => $tipo_doacao_id
            ]);

            return $resposta;
        } catch (\Throwable $th) {
            $resposta = new stdClass;
            $resposta->erro = TRUE;
            $resposta->msg = $th->getMessage();

            return $resposta;
        }
    }

    public function excluir($id)
    {
        try {
            $resposta = new stdClass;
            $resposta->erro = FALSE;
            $resposta->msg = NULL;

            DB::table($this->tabela)->where("id", "=", $id)->delete();

            return $resposta;
        } catch (\Throwable $th) {
            $resposta = new stdClass;
            $resposta->erro = TRUE;
            $resposta->msg = $th->getMessage();
        }
    }
}