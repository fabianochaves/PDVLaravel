<?php

class Venda
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function verItens($dados)
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $id_venda = $dados['id_venda'];

        $query = $this->conn->prepare("
            SELECT * FROM itens_venda
            INNER JOIN produtos ON id_produto = cod_produto_venda
            WHERE cod_venda = :id_venda
        ");
        $query->bindParam(':id_venda', $id_venda);
        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao listar itens da venda: " . $e->getMessage());
        }

        $itens = $query->fetchAll(PDO::FETCH_ASSOC);

        $response = array(
            "status" => 1,
            "itens" => $itens
        );

        return $response;
    }

    public function listar($dados)
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        if($dados['is_ativo'] == 1){
            $where = "WHERE status_venda = 1";
        }
        else if($dados['is_ativo'] == 0){
            $where = "WHERE status_venda = 0";
        }
        else{
            $where = "";
        }

        $query = $this->conn->prepare("
            SELECT * FROM vendas
            $where ORDER BY datetime_venda DESC
        ");

        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao listar vendas: " . $e->getMessage());
        }

        $vendas = $query->fetchAll(PDO::FETCH_ASSOC);

        return $vendas;
    }

    public function cadastrar($dados){

            $datetime_venda = date('Y-m-d H:i:s');
            $valor_total_venda = $dados['totalValorVenda'];
            $valor_imposto_venda = $dados['totalValorImposto'];
            $status_venda = 1;

            $insercao = $this->conn->prepare("
                INSERT INTO vendas (datetime_venda, valor_total_venda, valor_imposto_venda, status_venda) 
                VALUES (:datetime_venda, :valor_total_venda, :valor_imposto_venda, :status_venda)
            ");
            $insercao->bindParam(':datetime_venda', $datetime_venda);
            $insercao->bindParam(':valor_total_venda', $valor_total_venda);
            $insercao->bindParam(':valor_imposto_venda', $valor_imposto_venda);
            $insercao->bindParam(':status_venda', $status_venda);

            if (!$insercao->execute()) {
                throw new PDOException("Erro ao inserir a venda na tabela de vendas: " . $insercao->errorInfo()[2]);
            }

            // Recuperar o ID da venda inserida
            $cod_venda = $this->conn->lastInsertId();

            // Inserir os itens da venda na tabela de itens_venda
            foreach ($dados['itens'] as $item) {
                $cod_produto_venda = $item['id_produto'];
                $qtd_produto_venda = $item['quantidade'];
                $valor_unitario_venda = str_replace(',', '.', str_replace('.', '', $item['valor_unitario']));
                $imposto_produto_venda = $item['percent_imposto'];
                $total_produto_venda = $item['valor_total_produto'];
                $total_imposto_venda = $item['valor_imposto'];
                $status_item_venda = 1;

                $stmt = $this->conn->prepare("
                INSERT INTO itens_venda (cod_venda, cod_produto_venda, qtd_produto_venda, valor_unitario_venda, imposto_produto_venda, total_produto_venda, total_imposto_venda, status_item_venda) 
                VALUES (:cod_venda, :cod_produto_venda, :qtd_produto_venda, :valor_unitario_venda, :imposto_produto_venda, :total_produto_venda, :total_imposto_venda, :status_item_venda)");
                $stmt->bindParam(':cod_venda', $cod_venda);
                $stmt->bindParam(':cod_produto_venda', $cod_produto_venda);
                $stmt->bindParam(':qtd_produto_venda', $qtd_produto_venda);
                $stmt->bindParam(':valor_unitario_venda', $valor_unitario_venda);
                $stmt->bindParam(':imposto_produto_venda', $imposto_produto_venda);
                $stmt->bindParam(':total_produto_venda', $total_produto_venda);
                $stmt->bindParam(':total_imposto_venda', $total_imposto_venda);
                $stmt->bindParam(':status_item_venda', $status_item_venda);

                if (!$stmt->execute()) {
                    throw new PDOException("Erro ao inserir a venda na tabela de vendas: " . $stmt->errorInfo()[2]);
                }
    
            }

            $response = array(
                "status" => 1,
                "message" => "Salvo com Sucesso!"
            );

            return $response;

    }
    
    public function carregarDadosVenda()
    {
        require_once 'Produtos.php';
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }
    
        $produtosClass = new Produtos($this->conn);
        $parametros = ['is_ativo' => 1];
        $produtos = $produtosClass->listarProdutos($parametros); 
       

        $form = '<div class="form-row">';
        $form .= '<div class="col-md-6 offset-md-3">';
        $form .= '<label for="produto">Selecione um produto:</label>';
        $form .= '<select class="form-control campos_novo_item" id="produto" name="produto">';
        $form .= '<option value="">Escolha...</option>';
        
        foreach ($produtos as $produto) {
            $form .= '<option value="' . $produto['id_produto'] . '">' . $produto['nome_produto'] . '</option>';
        }
        
        $form .= '</select>';
        $form .= '<div class="col-md-12">';
        $form .= '</br>';

        $form .= '<label for="quantidade">Quantidade:</label>';
        $form .= '<input type="number" class="form-control inputs_novo_item campos_novo_item" id="quantidade" name="quantidade" min="1" required>';
        $form .= '</div>';
        $form .= '<div class="col-md-12">';
        $form .= '</br>';

        $form .= '<label for="quantidade">Preço Unitário (R$):</label>';
        $form .= '<input readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="valor_unitario" name="valor_unitario">';
        $form .= '</div>';
        $form .= '<div class="col-md-12">';
        $form .= '</br>';

        $form .= '<label for="quantidade">% Imposto:</label>';
        $form .= '<input readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="percent_imposto" name="percent_imposto">';
        $form .= '</div>';
        $form .= '<div class="col-md-12">';
        $form .= '</br>';

        $form .= '<label for="quantidade">Valor Imposto (R$):</label>';
        $form .= '<input readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="valor_imposto" name="valor_imposto">';
        $form .= '</div>';
        $form .= '</br>';

        $form .= '<label for="quantidade">Valor Total do Produto (R$):</label>';
        $form .= '<input readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="valor_total_produto" name="valor_total_produto">';
        $form .= '</div>';
        $form .= '</div>';
        
        return $form;
    }

}
