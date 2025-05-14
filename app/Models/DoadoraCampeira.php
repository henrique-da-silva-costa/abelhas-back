<?php

namespace App\Models;

use App\Http\Controllers\Tabela;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class DoadoraCampeira extends Model
{
    private $tabela;
    private $tabelaColmeia;
    private $tabelaTipoDoacao;
    private $tabelaTipoDivisao;

    public function __construct()
    {
        $this->tabela = Tabela::DOADORA_CAMPEIRA;
        $this->tabelaColmeia = Tabela::COLMEIA;
        $this->tabelaTipoDoacao = Tabela::TIPO_DOACAO;
        $this->tabelaTipoDivisao = Tabela::TIPO_DIVISAO;
    }

    public function pegarTodos($usuario_id, $filtros)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }

            $nome = isset($filtros["nome"]) ? $filtros["nome"] : NULL;

            $sql = DB::table($this->tabela);
            $sql->where("usuario_id", "=", $usuario_id);
            $sql->where("{$this->tabelaColmeia}.usuario_id", "=", $usuario_id);
            if ($nome) {
                $sql->where("{$this->tabelaColmeia}.nome", "like", "%" . $nome . "%");
            }
            $sql->leftJoin($this->tabelaColmeia, "{$this->tabela}.colmeia_id", "=", "{$this->tabelaColmeia}.id");
            $sql->leftJoin($this->tabelaTipoDoacao, "{$this->tabela}.tipo_doacao_id", "=", "{$this->tabelaTipoDoacao}.id")->select([
                "{$this->tabela}.*",
                "{$this->tabelaColmeia}.nome AS colmeia_nome",
                "{$this->tabelaTipoDoacao}.tipo AS tipo_doacao_tipo"
            ]);
            $dados = $sql->paginate(3);

            return $dados;
        } catch (\Throwable $th) {
            return [$th->getMessage()];
        }
    }

    public function pegarTodosSelect($usuario_id, $especie_id)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }

            $dados = DB::table($this->tabela)
                ->where("usuario_id", "=", $usuario_id)
                ->where("{$this->tabelaColmeia}.especie_id", "=", $especie_id)
                ->leftJoin($this->tabelaColmeia, "{$this->tabela}.colmeia_id", "=", "{$this->tabelaColmeia}.id")
                ->leftJoin($this->tabelaTipoDoacao, "{$this->tabela}.tipo_doacao_id", "=", "{$this->tabelaTipoDoacao}.id")->select([
                    "{$this->tabela}.*",
                    "{$this->tabelaColmeia}.nome AS colmeia_nome",
                    "{$this->tabelaColmeia}.genero_id AS colmeia_genero_id",
                    "{$this->tabelaTipoDoacao}.tipo AS tipo_doacao_tipo"
                ])
                ->get();

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

    public function pegarTipoDivisao()
    {
        try {
            $dados = DB::table($this->tabelaTipoDivisao)->get();

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

    public function existeDoadora($dados)
    {
        try {
            $colmeia_id = isset($dados["colmeia_id"]) ? $dados["colmeia_id"] : 0;
            $id = isset($dados["id"]) ? $dados["id"] : 0;

            if (!is_numeric($colmeia_id)) {
                return NULL;
            }

            $dados = DB::table($this->tabela)->where("id", "<>", $id)->where("colmeia_id", "=", $colmeia_id)->first();

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
            $resposta->id = NULL;

            $colmeia_id = isset($dados["colmeia_id"]) ? $dados["colmeia_id"] : NULL;
            $tipo_doacao_id = isset($dados["tipo_doacao_id"]) ? $dados["tipo_doacao_id"] : NULL;

            DB::table($this->tabela)->insert([
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