<?php
include "../../conexao.php";

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$nome = $_POST["nome_estampa"];
$descricao = $_POST["descricao"];
$id_colecao = $_POST["id_colecao"];

// ================================
// PROCESSAMENTO DA IMAGEM
// ================================
$imagem = null;

if (!empty($_FILES["imagem_estampa"]["name"])) {
    $ext = strtolower(pathinfo($_FILES["imagem_estampa"]["name"], PATHINFO_EXTENSION));
    $permitidas = ["jpg", "jpeg", "png", "webp"];

    if (in_array($ext, $permitidas)) {
        $novoNome = uniqid("est_") . "." . $ext;
        move_uploaded_file($_FILES["imagem_estampa"]["tmp_name"], "../../uploads/$novoNome");
        $imagem = "uploads/" . $novoNome;
    }
}

if ($id === 0) {
    $stmt = $con->prepare("INSERT INTO estampas (nome_estampa, descricao, imagem_estampa, id_colecao) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nome, $descricao, $imagem, $id_colecao);
} else {
    if ($imagem) {
        $stmt = $con->prepare("UPDATE estampas SET nome_estampa=?, descricao=?, imagem_estampa=?, id_colecao=? WHERE id_estampa=?");
        $stmt->bind_param("sssii", $nome, $descricao, $imagem, $id_colecao, $id);
    } else {
        $stmt = $con->prepare("UPDATE estampas SET nome_estampa=?, descricao=?, id_colecao=? WHERE id_estampa=?");
        $stmt->bind_param("ssii", $nome, $descricao, $id_colecao, $id);
    }
}

$stmt->execute();
header("Location: index.php");
?>
