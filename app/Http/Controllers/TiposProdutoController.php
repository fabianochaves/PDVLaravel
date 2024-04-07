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

    public function indexViewConsulta(Request $request){

        $tiposProdutos = Tipos::all();
        return view('consultaTipos', compact('tiposProdutos'));
    }

    public function listarTipos()
    {
        $tipos = Tipos::all();
        return $tipos;
    }

    public function salvarEdicao(Request $request)
    {
        $id_tipo = $request->get('id_tipo_produto');
        $novo_nome = $request->get('novo_nome');
        $novo_imposto = $request->get('novo_imposto');
        $novo_imposto = str_replace(".","", $novo_imposto);
        $novo_imposto = str_replace(",",".", $novo_imposto);
    
        // Encontre o modelo pelo ID
        $tipo = Tipos::findOrFail($id_tipo);
    
        // Atualize os campos desejados
        $tipo->nome_tipo_produto = $novo_nome;
        $tipo->imposto_tipo_produto = $novo_imposto;
    
        // Salve as mudanças no banco de dados
        $tipo->save();
    
        // Retorne uma resposta de sucesso ou redirecione para outra página
        return response()->json(['message' => 'Tipo atualizado com sucesso', 'status' => 1]);
    }

    public function alterarStatus(Request $request)
    {
        $id_tipo = $request->get('id_tipo_produto');
        $novo_status = $request->get('novo_status');
    
        // Encontre o modelo pelo ID
        $tipo = Tipos::findOrFail($id_tipo);
    
        // Atualize os campos desejados
        $tipo->status_tipo_produto = $novo_status;
    
        // Salve as mudanças no banco de dados
        $tipo->save();
    
        // Retorne uma resposta de sucesso ou redirecione para outra página
        return response()->json(['message' => 'Status atualizado com sucesso', 'status' => 1]);
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
