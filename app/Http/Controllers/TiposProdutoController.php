<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipos;

class TiposProdutoController extends Controller
{
    //
    public function indexViewCadastro(Request $request){
       
        $msg = "";
        
        if ($request->has('error') && isset($request->error)) {
      
            if($request->get('error') == 1){
                $msg = "Nome já existente!";
            }
            else{
                $msg = "Cadastrado com Sucesso!";
            }
        }
        
        return view('cadastroTipos', ['msg_status' => $msg]);
    }

    public function cadastrar(Request $request){
        $regras = [
            "nome_tipo_produto" => 'required',
            "imposto_tipo_produto" => 'required|max:5'
        ];

        $feedback = [
            'nome_tipo_produto.required' => 'Preencha o Nome!',
            'imposto_tipo_produto.required' => 'Preencha o Imposto!',
            'imposto_tipo_produto.min' => 'Imposto não pode ser maior que 100!'
        ];

        $request->validate($regras, $feedback);
        $request->merge(['status_tipo_produto' => 1]);

        $tipo = Tipos::where('nome_tipo_produto', $request->get('nome_tipo_produto'))->first();

        if ($tipo) {

            return redirect()->route('cadastroTipos', ['error' => 1]);

        } else {
          if($request->input('_token') != ''){
            $tipos = new Tipos();
            $tipos->create($request->all());
            return redirect()->route('cadastroTipos', ['error' => 0]);
          }
        }
    }

}
