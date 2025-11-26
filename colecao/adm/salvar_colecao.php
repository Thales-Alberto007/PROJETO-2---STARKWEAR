<?php
include "../../conexao.php";

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$nome = $_POST["nome_colecao"];
$descricao = $_POST["descricao"];
$desconto = floatval($_POST["desconto"]);

if ($id === 0) {
    $stmt = $con->prepare("INSERT INTO colecoes (nome_colecao, descricao, desconto) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $nome, $descricao, $desconto);
} else {
    $stmt = $con->prepare("UPDATE colecoes SET nome_colecao=?, descricao=?, desconto=? WHERE id_colecao=?");
    $stmt->bind_param("ssdi", $nome, $descricao, $desconto, $id);
}

$stmt->execute();
header("Location: index.php");
?>
