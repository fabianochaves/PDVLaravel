<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include("Connections/connpdo.php");
require("Errors.php");

extract($_POST);

try {
    if (!isset($conn)) {
        throw new PDOException("Falha na conexão");
    }

    $conn->beginTransaction();

    if (!file_exists("classes/$classe.php")) {
        throw new FileNotFoundError("Caminho de classe não encontrado: 'classes/$classe.php'");
    }

    include("classes/$classe.php");

    $classInstance = new $classe($conn);

    if (!method_exists($classInstance, $funcao)) {
        throw new Exception("Método '$funcao' não encontrado na classe '$classe'");
    }

    $response = $classInstance->$funcao($_POST);

    $conn->commit();
    echo json_encode($response);

} catch (PDOException $e) {
    handleException($e, $conn, "Erro PDO");
} catch (SyntaxError $e) {
    handleException($e, $conn, "Erro de Sintaxe");
} catch (FileNotFoundError $e) {
    handleException($e, $conn, "Erro Arquivo não encontrado");
} catch (Exception $e) {
    handleException($e, $conn, "Erro");
}

function handleException($e, $conn, $message) {
    if ($conn && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(["status" => 0, "message" => $message, "body" => $e->getMessage()]);
}