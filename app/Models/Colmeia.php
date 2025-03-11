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

    public function cadastrar($dados)
    {
        try {
            $resposta = new stdClass;
            $resposta->msg = NULL;
            $resposta->erro = FALSE;

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
            ]);

            return $resposta;
        } catch (\Throwable $th) {
            $resposta = new stdClass;
            $resposta->msg = $th->getMessage();
            $resposta->erro = TRUE;

            return $resposta;
        }
    }
}