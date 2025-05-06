<?php

namespace App\Models;

use App\Http\Controllers\Tabela;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use stdClass;

class Usuario extends Model
{
    private $tabela;

    public function __construct()
    {
        $this->tabela = Tabela::USUARIO;
    }

    public function cadastrar($dados)
    {
        try {
            $retorno = new stdClass;
            $retorno->msg = NULL;
            $retorno->erro = FALSE;

            $nome = isset($dados["nome"]) ? $dados["nome"] : NULL;
            $img = isset($dados["img"]) ? $dados["img"] : NULL;
            $email = isset($dados["email"]) ? $dados["email"] : NULL;
            $senha = isset($dados["senha"]) ? $dados["senha"] : NULL;

            DB::table($this->tabela)->insert([
                "nome" => $nome,
                "img" => $img,
                "email" => $email,
                "senha" => Hash::make($senha)
            ]);

            return $retorno;
        } catch (\Throwable $th) {
            $retorno = new stdClass;
            $retorno->msg = $th->getMessage();
            $retorno->erro = TRUE;

            return $retorno;
        }
    }

    public function existeEmail($dados)
    {
        try {
            $email = isset($dados["email"]) ? $dados["email"] : NULL;

            $dado = DB::table($this->tabela)->where("email", "=", $email)->first("email");

            if ($dado) {
                return TRUE;
            }

            return FALSE;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function existeUsuario($dados)
    {
        try {
            $email = isset($dados["email"]) ? $dados["email"] : NULL;
            $senha = isset($dados["senha"]) ? $dados["senha"] : NULL;

            $usuario = DB::table($this->tabela)->where("email", "=", $email)->first();

            if (!$usuario) {
                return FALSE;
            }

            if (!Hash::check($senha, $usuario->senha)) {
                return FALSE;
            }

            return $usuario;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function recuperarSenha($dados)
    {
        try {
            $email = isset($dados["email"]) ? $dados["email"] : NULL;
            $senha = isset($dados["senha"]) ? $dados["senha"] : NULL;
            $confirmaSenha = isset($dados["confirmaSenha"]) ? $dados["confirmaSenha"] : NULL;

            $usuario = DB::table($this->tabela)->where("email", "=", $email)->first(["senha"]);

            if (!Hash::check($senha, $usuario->senha)) {
                return TRUE;
            }

            DB::table($this->tabela)->where("email", "=", $email)->update([
                "senha" => Hash::make($confirmaSenha)
            ]);

            return FALSE;
        } catch (\Throwable $th) {
            print_r($th->getMessage());
            return NULL;
        }
    }
}