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
            $genero = isset($filtros["genero"]) ? $filtros["genero"] : NULL;
            $especie = isset($filtros["especie"]) ? $filtros["especie"] : NULL;

            $sql = DB::table($this->tabela);
            $sql->where("usuario_id", "=", $usuario_id);
            $sql->where("{$this->tabelaColmeia}.usuario_id", "=", $usuario_id);
            if ($nome) {
                $sql->where("{$this->tabelaColmeia}.nome", "like", "%" . $nome . "%");
            }
            if ($genero) {
                $sql->where("{$this->tabelaColmeia}.genero_id", "=",  $genero);
            }
            if ($especie) {
                $sql->where("{$this->tabelaColmeia}.especie_id", "=",  $especie);
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

    public function pegarPorColmeiaId($id)
    {
        try {
            if (!is_numeric($id)) {
                return NULL;
            }

            $dados = DB::table($this->tabela)->where("colmeia_id", "=", $id)->orderBy("desc")->first();
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

    public function existeColmeia($colmeia_id)
    {
        try {
            $dado = DB::table($this->tabela)->where("colmeia_id", "=", $colmeia_id)->first();

            return $dado;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function cadastrar($colmeia_id)
    {
        try {
            $resposta = new stdClass;
            $resposta->erro = FALSE;
            $resposta->msg = NULL;
            $resposta->id = NULL;

            DB::table($this->tabela)->insert([
                "colmeia_id" => $colmeia_id,
                "tipo_doacao_id" => 2
            ]);

            return $resposta;
        } catch (\Throwable $th) {
            $resposta = new stdClass;
            $resposta->erro = TRUE;
            $resposta->msg = $th->getMessage();

            return $resposta;
        }
    }
    public function editar($id, $data_doacao)
    {
        try {
            $resposta = new stdClass;
            $resposta->erro = FALSE;
            $resposta->msg = NULL;

            DB::table($this->tabela)->where("id", "=", $id)->update([
                "data_doacao" => $data_doacao,
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