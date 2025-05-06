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
        $request->validate([
            "email" => "required",
            "senha" => "required"
        ]);

        $inputs = $request->all();

        $usuario = $this->usuario->existeUsuario($inputs);

        if (!$usuario) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail ou senha incorreto"]);
        }

        return response()->json(["erro" => FALSE, "usuario" => ["id" => $usuario->id, "email" => $usuario->email, "nome" => $usuario->nome, "img" => $usuario->img]]);
    }

    public function verificarEmail(Request $request)
    {
        $request->validate(["email" => "required"]);

        $inputs = $request->all();

        $existeEmail = $this->usuario->existeEmail($inputs);

        if (!$existeEmail) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail não encontrado!"]);
        }

        return response()->json(["erro" => FALSE, "msg" => "E-mail disponível!"]);
    }

    public function verificarEmailApp(Request $request)
    {
        $request->validate(["email" => "required"]);

        $inputs = $request->all();

        $existeEmail = $this->usuario->existeEmail($inputs);

        if (!$existeEmail) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail não encontrado!"]);
        }

        return response()->json(["erro" => FALSE, "msg" => "E-mail disponível!"]);
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            "nome" => "required",
            "email" => "required",
            "senha" => "required",
            "img" => "image|mimes:jpeg,png,jpg,gif|max:2048"
        ]);

        $inputs = $request->all();

        $imgCaminho = $request->file('img')->store('imagens', 'public');

        $inputs["img"] = "http://" . $_SERVER["HTTP_HOST"] . "/" . "storage" . "/" . $imgCaminho;
        $inputs["img_caminho"] = $imgCaminho;

        $existeEmail = $this->usuario->existeEmail($inputs);

        if ($existeEmail) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail já cadastrado!"]);
        }

        $cadastrar = $this->usuario->cadastrar($inputs);

        if ($cadastrar->erro) {
            return response()->json(["erro" => TRUE, "msg" => $cadastrar->msg]);
        }

        return response()->json(["erro" => FALSE, "msg" => "Usuário cadastrado com sucesso!"]);
    }

    public function recuperarSenha(Request $request)
    {
        $request->validate([
            "email" => "required",
            "senha" => "required",
            "novaSenha" => "required",
            "confirmaSenha" => "required"
        ]);

        $inputs = $request->all();

        $email = isset($inputs["email"]) ? $inputs["email"] : NULL;
        $novaSenha = isset($inputs["novaSenha"]) ? $inputs["novaSenha"] : NULL;
        $confirmaSenha = isset($inputs["confirmaSenha"]) ? $inputs["confirmaSenha"] : NULL;


        if ($novaSenha != $confirmaSenha) {
            return response()->json(["erro" => TRUE, "msg" => "As senhas não são iguais"]);
        }

        $existeEmail = $this->usuario->existeEmail($inputs);

        if (!$existeEmail) {
            return response()->json(["erro" => TRUE, "msg" => "E-mail não encontrado"]);
        }

        $recuperarSenha = $this->usuario->recuperarSenha($inputs);

        if ($recuperarSenha) {
            return response()->json(["erro" => TRUE, "msg" => "Senha incorreta"]);
        }

        return response()->json(["erro" => FALSE, "msg" => "Senha recuperada com sucesso!"]);
    }
}