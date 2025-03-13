<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario;
    }

    public function login(Request $request)
    {
        $inputs = $request->all();

        $existeUsuario = $this->usuario->existeUsuario($inputs);

        if ($existeUsuario) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail ou senha incorreto"]);
        }

        return response()->json(["erro" => FALSE, "msg" => "Usuário cadastrado com sucesso!"]);
    }

    public function verificarEmail(Request $request)
    {
        $inputs = $request->all();

        $existeEmail = $this->usuario->existeEmail($inputs);

        if ($existeEmail) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail já cadastrado!"]);
        }

        return response()->json(["erro" => FALSE, "msg" => "E-mail disponível!"]);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            "nome" => "required",
            "email" => "required",
            "senha" => "required"
        ]);

        $inputs = $request->all();

        $existeEmail = $this->usuario->existeEmail($inputs);

        if ($existeEmail) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail já cadastrado!"]);
        }

        $cadastrar = $this->usuario->cadastrar($inputs);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        return response()->json(["msg" => "Usuário cadastrado com sucesso!"]);
    }

    public function recuperarSenha(Request $request)
    {
        $inputs = $request->all();

        $recuperarSenha = $this->usuario->recuperarSenha($inputs);

        if ($recuperarSenha) {
            return response()->json(["erro" => TRUE, "msg" => "Senha incorreta"]);
        }

        return response()->json(["erro" => FALSE, "msg" => "Senha recuperada com sucesso!"]);
    }
}
