<?php
class Dashboard
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function buscaVendasMesAMes()
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("
            SELECT EXTRACT(MONTH FROM datetime_venda) as mes, SUM(valor_total_venda) as total
            FROM vendas
            WHERE EXTRACT(YEAR FROM datetime_venda) = EXTRACT(YEAR FROM NOW())
            GROUP BY mes;
        ");

        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao buscar as vendas mês a mês: " . $e->getMessage());
        }

        $dadosVendasMesAMes = $query->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($dadosVendasMesAMes);
    }


    public function buscaTotalVendasMes()
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("
            SELECT SUM(valor_total_venda) as total 
            FROM vendas 
            WHERE EXTRACT(MONTH FROM datetime_venda) = EXTRACT(MONTH FROM NOW());
        ");
        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao buscar o total de vendas do mês: " . $e->getMessage());
        }

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result['total'] == 0){
            $retorno = "0,00";
        }
        else{
            $retorno = number_format($result['total'], 2, ',', '.');;
        }
        return $retorno;
    }

    public function buscaTotalVendasUltimos7Dias()
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("
            SELECT SUM(valor_total_venda) as total
            FROM vendas
            WHERE datetime_venda >= NOW() - INTERVAL '7 days'        
        ");
        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao buscar o total de vendas dos últimos 7 dias: " . $e->getMessage());
        }

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result['total'] == 0){
            $retorno = "0,00";
        }
        else{
            $retorno = number_format($result['total'], 2, ',', '.');;
        }
        return $retorno;
    }

    public function buscaTotalVendasHoje()
    {
        if (!isset($this->conn)) {
            throw new PDOException("Falha na conexão");
        }

        $query = $this->conn->prepare("
            SELECT SUM(valor_total_venda) as total
            FROM vendas
            WHERE DATE(datetime_venda) = CURRENT_DATE;
        ");
        try {
            $query->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao buscar o total de vendas de hoje: " . $e->getMessage());
        }

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result['total'] == 0){
            $retorno = "0,00";
        }
        else{
            $retorno = number_format($result['total'], 2, ',', '.');;
        }
        return $retorno;
    }
}

?>
