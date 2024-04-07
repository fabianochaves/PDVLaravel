<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produtos;
use App\Tipos;
use App\Venda;
use App\ItensVenda;

class VendaController extends Controller
{
    public function indexViewCadastro(Request $request){
        
        $msg = "";
        
        if ($request->has('error') && isset($request->error)) {
    
            if($request->get('error') == 1){
                $msg = "Venda já existente!";
            }
            else{
                $msg = "Cadastrado com Sucesso!";
            }
        }

        $produtos = $this->listarProdutos(['is_ativo' => 1]);
        
        return view('cadastroVenda', ['msg_status' => $msg, 'produtos' => $produtos]);
    }

    public function indexViewConsulta(Request $request){

        $vendas = Venda::all();
        return view('consultaVendas', compact('vendas'));
    }

    public function obterPrecos(Request $request)
    {
        $produtoId = $request->produto_id;
        $produto = Produtos::find($produtoId);

        $tipoProduto = $produto->tipo_produto;
        $tipo = Tipos::find($tipoProduto);
        $imposto = $tipo->imposto_tipo_produto;
      
          if ($produto) {
            $valorTotal = $produto->preco_venda_produto * $request->quantidade;

            $valorTotal = number_format($valorTotal,2,",",".");
            $preco_venda_produto = number_format($produto->preco_venda_produto,2,",",".");
            $imposto = number_format($imposto,2,",",".");
            
            return response()->json([
                'preco_unitario' => $preco_venda_produto,
                'imposto' => $imposto,
                'valor_total' => $valorTotal,
            ]);
        } else {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }
    }

    public function listarVendas()
    {
        $vendas = Venda::all();
        return response()->json($vendas);
    }

    public function listarItens($idVenda)
    {
        
        $itensVenda = ItensVenda::with('produto')->where('cod_venda', $idVenda)->orderBy('id_item_venda', 'DESC')->get();
        foreach ($itensVenda as $item) {
            $item->valor_unitario_venda = number_format($item->valor_unitario_venda, 2, ',', '.');
            $item->imposto_produto_venda = number_format($item->imposto_produto_venda, 2, ',', '.');
            $item->total_produto_venda = number_format($item->total_produto_venda, 2, ',', '.');
            $item->total_imposto_venda = number_format($item->total_imposto_venda, 2, ',', '.');
        
        }

        return response()->json($itensVenda);
    }
    
    public function listarProdutos($dados)
    {
        if($dados['is_ativo'] == 1){
            $produtos = Produtos::where('status_produto', 1)->get();
        }
        else if($dados['is_ativo'] == 0){
            $produtos = Produtos::all();
        }

        return $produtos;
    }

    public function finalizarVenda(Request $request)
    {
        $id_venda = $request->get('id_venda');
        $venda = Venda::find($id_venda);
        if ($venda) {
            $venda->status_venda = 1;
            $venda->save();
        }

        $itensVenda = ItensVenda::where('cod_venda', $id_venda)->get();
        foreach ($itensVenda as $item) {
            $item->status_item_venda = 1;
            $item->save();
        }
       
        return response()->json(['message' => 'Venda Finalizada com Sucesso', 'id_venda' => $id_venda]);
    }
    public function deletarItem(Request $request)
    {
        $id_item_venda = $request->get('id_item_venda');
        $item = ItensVenda::find($id_item_venda);
    
        if (!$item) {
            return response()->json(['error' => 'Item não encontrado'], 404);
        }
    
        $item->delete();
        return response()->json(['message' => 'Item deletado com sucesso', 'id_item_venda' => $id_item_venda]);
    }

    public function cadastrar(Request $request)
    {
        session_start();
        $tabela_vazia = $request->get('tabela_vazia');
        $produto = $request->get('produto');
        $quantidade = $request->get('quantidade');
        $valor_unitario = $request->get('valor_unitario');
        $percent_imposto = $request->get('percent_imposto');
        $valor_total_produto = $request->get('valor_total_produto');

        $valor_unitario = str_replace(".","", $valor_unitario);
        $valor_unitario = str_replace(",",".", $valor_unitario);

        $valor_total_produto = str_replace(".","", $valor_total_produto);
        $valor_total_produto = str_replace(",",".", $valor_total_produto);

        $percent_imposto = str_replace(",",".", $percent_imposto);


        if ($tabela_vazia == 1) {

            unset($_SESSION['id_venda_andamento']);
            
            $novaVenda = new Venda();
            $novaVenda->datetime_venda = now();
            $novaVenda->valor_total_venda = $valor_total_produto;
            $novaVenda->status_venda = 0;
            $novaVenda->save();

            $idVenda = $novaVenda->getKey();

            $itemVenda = new ItensVenda();
            $itemVenda->cod_venda = $idVenda;
            $itemVenda->cod_produto_venda = $produto;
            $itemVenda->qtd_produto_venda = $quantidade;
            $itemVenda->valor_unitario_venda = $valor_unitario;
            $itemVenda->imposto_produto_venda = $percent_imposto;
            $itemVenda->total_produto_venda = $valor_total_produto;
            $itemVenda->total_imposto_venda = 0;
            $itemVenda->status_item_venda = 0;
            $itemVenda->save();

           
            $_SESSION['id_venda_andamento'] = $idVenda;
    
        } else {
            // Resgatar o último ID da tabela vendas
            $idVenda = $_SESSION['id_venda_andamento'];
    
            // Inserir o item da venda
            $itemVenda = new ItensVenda();
            $itemVenda->cod_venda = $idVenda;
            $itemVenda->cod_produto_venda = $produto;
            $itemVenda->qtd_produto_venda = $quantidade;
            $itemVenda->valor_unitario_venda = $valor_unitario;
            $itemVenda->imposto_produto_venda = $percent_imposto;
            $itemVenda->total_produto_venda = $valor_total_produto;
            $itemVenda->total_imposto_venda = 0; // Inicializa o valor total do imposto como 0
            $itemVenda->status_item_venda = 0; // Define o status do item da venda como 1
            $itemVenda->save();

            $venda = Venda::find($idVenda);
            $venda->valor_total_venda += $valor_total_produto; // Somar o valor do item recém inserido
            $venda->save();
    
        }
        
        return response()->json(['id_venda' => $idVenda]);
    }

}
