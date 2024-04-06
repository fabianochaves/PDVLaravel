<?php

class Login {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function logar($dados) {

        if (
            isset($dados['user']) &&
            isset($dados['password']) &&
            !empty($dados['user']) &&
            !empty($dados['password'])
        ) {
            $usuario = addslashes($dados['user']);
            $senha = addslashes(md5($dados['password']));

            $busca_user = $this->conn->prepare(
                "SELECT 
                    * 
                FROM 
                    usuarios u 
                INNER JOIN 
                    perfis p 
                ON 
                    u.perfil_usuario = p.id_perfil
                WHERE 
                    u.login_usuario = '$usuario' 
                AND 
                    u.senha_usuario = '$senha'"
            );

            $busca_user->execute();

            if ($busca_user->rowCount() >= 1) {
             
                $row_user = $busca_user->fetch(PDO::FETCH_ASSOC);

                $_SESSION['user'] = $row_user['id_usuario'];
                $_SESSION['nome'] = $row_user['nome_usuario'];
                $_SESSION['perfil'] = $row_user['perfil_usuario'];
                $_SESSION['nome_sistema'] = "Sistema de Vendas";

                $response = array(
                    "status" => 1,
                    "sessao" => $_SESSION['user']
                );
       
            } else {
                throw new Exception('Usuário e/ou senha inválidos');
            }
        } else {
            throw new Exception("Preencha os campos!");
        }

        return $response;
    }
}

