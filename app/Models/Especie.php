<?php

namespace App\Models;

use App\Http\Controllers\Tabela;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Especie extends Model
{
    private $tabela;

    public function __construct()
    {
        $this->tabela = Tabela::ESPECIE;
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

    public function pegarPorId($id)
    {
        try {
            $dados = DB::table($this->tabela)->where("id", "=", $id)->first();

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarPorGeneroId($genero_id)
    {
        try {
            $dados = DB::table($this->tabela)->where("genero_id", "=", $genero_id)->get();

            return $dados;
        } catch (\Throwable $th) {
            return [];
        }
    }
}