<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produtos;
use App\Tipos;

class ProdutoController extends Controller
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

        $tipos = Tipos::all();
        
        return view('cadastroProduto', ['msg_status' => $msg], compact('tipos'));
    }

    public function indexViewConsulta(Request $request){

        $produtos = Produtos::with('tipoProduto')->get();
        return view('consultaProdutos', compact('produtos'));
    }

    public function listarProdutos()
    {
        $produtos = Produtos::with('tipoProduto')->get();
        return $produtos;
    }

    
    public function alterarStatus(Request $request)
    {
        $id_produto = $request->get('id_produto');
        $novo_status = $request->get('novo_status');
        $produto = Produtos::findOrFail($id_produto);
        $produto->status_produto = $novo_status;
        $produto->save();
    
        return response()->json(['message' => 'Status atualizado com sucesso', 'status' => 1]);
    }

    public function salvarEdicao(Request $request)
    {
        $id_produto = $request->get('id_produto');
        $tipo_produto = $request->get('tipo_produto');
        $nome_produto = $request->get('nome_produto');
        $preco_venda_produto = $request->get('preco_venda_produto');
        $preco_custo_produto = $request->get('preco_custo_produto');
    
        $produto = Produtos::findOrFail($id_produto);
        $produto->nome_produto = $nome_produto;
        $produto->tipo_produto = $tipo_produto;
        $produto->preco_venda_produto = $preco_venda_produto;
        $produto->preco_custo_produto = $preco_custo_produto;
        $produto->save();

        return response()->json(['message' => 'Produto atualizado com sucesso', 'status' => 1]);
    }

    public function cadastrar(Request $request){
        
        $regras = [
            "nome_produto" => 'required',
            "tipo_produto" => 'required',
            "preco_venda_produto" => 'required',
            "preco_custo_produto" => 'required',
        ];

        $feedback = [
            'nome_produto.required' => 'Preencha o Nome!',
            'tipo_produto.required' => 'Preencha o Tipo!',
            'preco_venda_produto.required' => 'Preencha o Preço de Venda!',
            'preco_custo_produto.required' => 'Preencha o Preço de Custo!',
        ];

        $request->validate($regras, $feedback);
        $request->merge(['status_produto' => 1]);

        // Tratamento dos valores de preço de venda e preço de custo
        $preco_venda_produto = str_replace('.', '', $request->input('preco_venda_produto'));
        $preco_venda_produto = str_replace(',', '.', $preco_venda_produto);
        $request->merge(['preco_venda_produto' => $preco_venda_produto]);

        $preco_custo_produto = str_replace('.', '', $request->input('preco_custo_produto'));
        $preco_custo_produto = str_replace(',', '.', $preco_custo_produto);
        $request->merge(['preco_custo_produto' => $preco_custo_produto]);


        $produto = Produtos::where('nome_produto', $request->get('nome_produto'))->first();
   
        if ($produto) {

            return redirect()->route('cadastroProdutos', ['error' => 1]);

        } else {
            if($request->input('_token') != ''){
                $produtos = new Produtos();
                $produtos->create($request->all());
                return redirect()->route('cadastroProdutos', ['error' => 0]);
            }
        }
    }

}
