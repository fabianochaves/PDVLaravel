<?php

class Tipos
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

        $nome_tipo_produto = $dados['novo_nome'];
        $imposto = str_replace(',', '.', $dados['novo_imposto']);
        $id_tipo_produto = $dados['id_tipo_produto'];


        $query = $this->conn->prepare("
            UPDATE tipos_produtos SET nome_tipo_produto = :nome_tipo_produto, imposto_tipo_produto = :imposto
            WHERE id_tipo_produto = :id_tipo_produto
        ");
    
        $query->bindParam(":nome_tipo_produto", $nome_tipo_produto);
        $query->bindParam(":imposto", $imposto);
        $query->bindParam(":id_tipo_produto", $id_tipo_produto);
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
        $id_tipo_produto = $dados['id_tipo_produto'];

        $query = $this->conn->prepare("
            UPDATE tipos_produtos SET status_tipo_produto = 
            CASE 
                WHEN :status_tipo_produto = 'Ativo' THEN 1
                WHEN :status_tipo_produto = 'Inativo' THEN 0
            END
            WHERE id_tipo_produto = :id_tipo_produto
        ");
    
        $query->bindParam(":status_tipo_produto", $novo_status);
        $query->bindParam(":id_tipo_produto", $id_tipo_produto);
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

    public function listar()
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("SELECT * FROM tipos_produtos ORDER BY nome_tipo_produto");

        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao listar tipos de produtos: " . $e->getMessage());
        }

        $tipos_produtos = $query->fetchAll(PDO::FETCH_ASSOC);

        return $tipos_produtos;
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
                    $colunas .= $chave . ', ';
                    $valores .= ':' . $chave . ', ';
                    $parametros[':' . $chave] = $valor;
                }
            
            }
            $colunas = rtrim($colunas, ', ');
            $valores = rtrim($valores, ', ');

            $parametros_verificacao = [
                ':nome_tipo_produto' => $dados['nome_tipo_produto']
            ];

            $busca = $this->conn->prepare("
                SELECT * FROM tipos_produtos
                WHERE nome_tipo_produto = :nome_tipo_produto
            ");

            foreach ($parametros_verificacao as $chave => &$valor) {
                $busca->bindParam($chave, $valor);
            }
            try {
                $busca->execute();
            } catch (PDOException $e) {
                throw new PDOException("Erro ao Consultar o Tipo de Produto: " . $e->getMessage());
            }

            if ($busca->rowCount() > 0) {
                throw new PDOException("Tipo já Cadastrado!");
            }

            $query = $this->conn->prepare("
                    INSERT INTO tipos_produtos ($colunas, status_tipo_produto) 
                    VALUES ($valores, 1)");

            foreach ($parametros as $chave => &$valor) {
                $query->bindParam($chave, $valor);
            }

            try {
                $query->execute();
            } catch (PDOException $e) {
                throw new PDOException("Erro ao Inserir o Tipo de Produto: " . $e->getMessage());
            }
            
            $response = array(
                "status" => 1,
                "message" => "Salvo com Sucesso!"
            );

            return $response;
    }
 
}
