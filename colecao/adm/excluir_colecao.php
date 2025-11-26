<?php
include "../../conexao.php";

$id = intval($_GET["id"]);

$stmt = $con->prepare("DELETE FROM colecoes WHERE id_colecao=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
?>
