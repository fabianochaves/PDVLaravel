<?php

class Produtos
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    
    public function editar($dados){
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $tipo_produto = $dados['tipo_produto'];
        $nome_produto = $dados['nome_produto'];
        $preco_venda_produto = $dados['preco_venda_produto'];
        $preco_custo_produto = $dados['preco_custo_produto'];
        $id_produto = $dados['id_produto'];

        $query = $this->conn->prepare("
            UPDATE produtos 
            SET 
                nome_produto = :nome_produto, 
                preco_venda_produto = :preco_venda_produto,
                preco_custo_produto = :preco_custo_produto,
                tipo_produto = :tipo_produto
            WHERE 
                id_produto = :id_produto
        ");
    
        $query->bindParam(":nome_produto", $nome_produto);
        $query->bindParam(":preco_venda_produto", $preco_venda_produto);
        $query->bindParam(":preco_custo_produto", $preco_custo_produto);
        $query->bindParam(":tipo_produto", $tipo_produto);
        $query->bindParam(":id_produto", $id_produto);
        try {
            $query->execute();
            $response = array(
                "status" => 1,
                "message" => "Atualizado com Sucesso!"
            );

            return $response;
        } catch (PDOException $e) {
            throw new PDOException("Erro ao Atualizar o o Tipo: " . $e->getMessage());
        }
    }

    public function alterarStatus($dados){
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $novo_status = $dados['novo_status'];
        $id_produto = $dados['id_produto'];

        $query = $this->conn->prepare("
            UPDATE produtos SET status_produto = 
            CASE 
                WHEN :status_produto = 'Ativo' THEN 1
                WHEN :status_produto = 'Inativo' THEN 0
            END
            WHERE id_produto = :id_produto
        ");
    
        $query->bindParam(":status_produto", $novo_status);
        $query->bindParam(":id_produto", $id_produto);
        try {
            $query->execute();
            $response = array(
                "status" => 1,
                "message" => "Atualizado com Sucesso!"
            );

        } catch (PDOException $e) {
            throw new PDOException("Erro ao Atualizar o Status: " . $e->getMessage());
        }

        return $response;
    }

    public function buscaDadosProduto($dados)
    {
        $id_produto = $dados['id_produto'];

        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("
            SELECT * FROM produtos 
            INNER JOIN tipos_produtos ON id_tipo_produto = tipo_produto
            WHERE id_produto = :id_produto
        ");
        $query->bindParam(":id_produto", $id_produto);
        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao buscar produto: " . $e->getMessage());
        }

        $dadosProduto = $query->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($dadosProduto);
    }

    public function listarCategorias()
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("SELECT id_tipo_produto, nome_tipo_produto FROM tipos_produtos WHERE status_tipo_produto = 1 ORDER BY nome_tipo_produto");

        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao listar categorias: " . $e->getMessage());
        }

        $categorias = $query->fetchAll(PDO::FETCH_ASSOC);

        return $categorias;
    }

    public function listarProdutos($dados)
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        if($dados['is_ativo'] == 1){
            $where = "WHERE status_produto = 1";
        }
        else if($dados['is_ativo'] == 0){
            $where = "WHERE status_produto = 0";
        }
        else{
            $where = "";
        }

        $query = $this->conn->prepare("
            SELECT * FROM produtos
            INNER JOIN tipos_produtos ON id_tipo_produto = tipo_produto
            $where ORDER BY nome_produto
        ");

        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao listar produtos: " . $e->getMessage());
        }

        $produtos = $query->fetchAll(PDO::FETCH_ASSOC);

        return $produtos;
    }

    public function cadastrar($dados)
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

            $colunas = '';
            $valores = '';
            $parametros = [];

            foreach ($dados as $chave => $valor) {
                if($chave != "classe" && $chave != "funcao"){
                    if($chave == "preco_venda_produto" || $chave == "preco_custo_produto"){
                        $valor = str_replace(".","",$valor);
                        $valor = str_replace(",",".",$valor);
                    }
                    $colunas .= $chave . ', ';
                    $valores .= ':' . $chave . ', ';
                    $parametros[':' . $chave] = $valor;
                }

            }
            $colunas = rtrim($colunas, ', ');
            $valores = rtrim($valores, ', ');

            $parametros_verificacao = [
                ':nome_produto' => $dados['nome_produto']
            ];

            $busca = $this->conn->prepare("
                SELECT * FROM produtos
                WHERE nome_produto = :nome_produto
            ");

            foreach ($parametros_verificacao as $chave => &$valor) {
                $busca->bindParam($chave, $valor);
            }
            try {
                $busca->execute();
            } catch (PDOException $e) {
                throw new PDOException("Erro ao Consultar o Produto: " . $e->getMessage());
            }

            if ($busca->rowCount() > 0) {
                throw new PDOException("Produto já Cadastrado!");
            }

            $query = $this->conn->prepare("
                    INSERT INTO produtos ($colunas, status_produto) 
                    VALUES ($valores, 1)");

            foreach ($parametros as $chave => &$valor) {
                $query->bindParam($chave, $valor);
            }

            try {
                $query->execute();
            } catch (PDOException $e) {
                throw new PDOException("Erro ao Inserir o Produto: " . $e->getMessage());
            }


            $response = array(
                "status" => 1,
                "message" => "Salvo com Sucesso!"
            );

            return $response;
    }

}