<?php

namespace App\Models;

use App\Http\Controllers\Tabela;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Colmeia extends Model
{
    private $tabela;
    private $tabelaDoadoraDisco;
    private $tabelaDoadoraCampeira;
    private $tabelaTipoDivisao;
    private $tabelaTipoDoacao;

    public function __construct()
    {
        $this->tabela = Tabela::COLMEIA;
        $this->tabelaDoadoraDisco = Tabela::DOADORA_DISCO;
        $this->tabelaDoadoraCampeira = Tabela::DOADORA_CAMPEIRA;
        $this->tabelaTipoDivisao = Tabela::TIPO_DIVISAO;
        $this->tabelaTipoDoacao = Tabela::TIPO_DOACAO;
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

    public function pegarTipoDivisao()
    {
        try {
            $dados = DB::table($this->tabelaTipoDivisao)->get();

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarColmeiasDivisoes($usuario_id)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }
            $dados = DB::table($this->tabela . ' as tabela_colmeia')
                ->where("tabela_colmeia.status_id", "=", 1)
                ->where("tabela_colmeia.usuario_id", "=", $usuario_id)
                ->leftJoin("{$this->tabelaDoadoraDisco} as dd", "tabela_colmeia.doadora_disco_id", "=", "dd.id")
                ->leftJoin("{$this->tabelaDoadoraCampeira} as dc", "tabela_colmeia.doadora_campeira_id", "=", "dc.id")
                ->rightJoin("{$this->tabela} as tabela_colmeia_disco", "tabela_colmeia_disco.id", "=", "dd.colmeia_id")
                ->rightJoin("{$this->tabela} as tabela_colmeia_campeira", "tabela_colmeia_campeira.id", "=", "dc.colmeia_id")
                ->distinct()
                ->select([
                    "tabela_colmeia.nome",
                    "tabela_colmeia.data_criacao",
                    "tabela_colmeia_disco.nome AS doadora_disco_nome",
                    "tabela_colmeia_campeira.nome AS doadora_campeira_nome",
                ])
                ->paginate(2);

            return $dados;
        } catch (\Throwable $th) {
            return [$th->getMessage()];
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

    public function pegarColmeiasMatrizesPaginacao($usuario_id)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }

            $dados = DB::table($this->tabela)
                ->where("status_id", "=", 2)
                ->where("usuario_id", "=", $usuario_id)
                ->paginate(3);

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

            if ($dados) {
                return TRUE;
            }

            return FALSE;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function pegarPorId($id)
    {
        try {
            $dados = DB::table($this->tabela)->where("id", "=", $id)->first(
                [
                    "id",
                    "nome",
                    "data_criacao",
                    "especie_id",
                    "genero_id",
                    "status_id",
                    "doadora_campeira_id",
                    "doadora_disco_id",
                    "tipo_divisao_id",
                    "usuario_id"
                ]
            );
            return $dados;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function pegarPorUsuarioId($usuario_id, $filtros)
    {
        try {
            if (!is_numeric($usuario_id)) {
                return [];
            }

            $nome = isset($filtros["nome"]) ? $filtros["nome"] : NULL;
            $status = isset($filtros["status"]) ? $filtros["status"] : NULL;
            $genero = isset($filtros["genero"]) ? $filtros["genero"] : NULL;

            $sql = DB::table($this->tabela)->where("usuario_id", "=", $usuario_id);
            if ($nome) {
                $sql->where("{$this->tabela}.nome", "like", "%" . $nome . "%");
            }
            if ($status) {
                $sql->where("{$this->tabela}.status_id", "=", $status);
            }
            if ($genero) {
                $sql->where("{$this->tabela}.genero_id", "=", $genero);
            }
            $sql->orderBy("id", "desc");
            $dados = $sql->paginate(5);

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
            $doadora_disco_id = isset($dados["doadora_disco_id"]) ? $dados["doadora_disco_id"] : NULL;
            $tipo_divisao_id = isset($dados["tipo_divisao_id"]) ? $dados["tipo_divisao_id"] : NULL;
            $doadora_campeira_id = isset($dados["doadora_campeira_id"]) ? $dados["doadora_campeira_id"] : NULL;
            $usuario_id = isset($dados["usuario_id"]) ? $dados["usuario_id"] : NULL;

            DB::table($this->tabela)->insert([
                "nome" => $nome,
                "data_criacao" => $data_criacao,
                "data_alteracao" => $data_alteracao,
                "data_divisao" => $data_divisao,
                "genero_id" => $genero_id,
                "especie_id" => $especie_id,
                "status_id" => $status_id,
                "doadora_disco_id" => $doadora_disco_id,
                "tipo_divisao_id" => $tipo_divisao_id,
                "doadora_campeira_id" => $doadora_campeira_id,
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
            $doadora_disco_id = isset($dados["doadora_disco_id"]) ? $dados["doadora_disco_id"] : NULL;
            $tipo_divisao_id = isset($dados["tipo_divisao_id"]) ? $dados["tipo_divisao_id"] : NULL;
            $doadora_campeira_id = isset($dados["doadora_campeira_id"]) ? $dados["doadora_campeira_id"] : NULL;
            $usuario_id = isset($dados["usuario_id"]) ? $dados["usuario_id"] : NULL;

            DB::table($this->tabela)->where("id", "=", $id)->update([
                "nome" => $nome,
                "data_criacao" => $data_criacao,
                "data_alteracao" => $data_alteracao,
                "data_divisao" => $data_divisao,
                "genero_id" => $genero_id,
                "especie_id" => $especie_id,
                "status_id" => $status_id,
                "doadora_disco_id" => $doadora_disco_id,
                "tipo_divisao_id" => $tipo_divisao_id,
                "doadora_campeira_id" => $doadora_campeira_id,
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
