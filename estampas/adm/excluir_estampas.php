<?php
include "../../conexao.php";

$id = intval($_GET["id"]);

$stmt = $con->prepare("DELETE FROM estampas WHERE id_estampa=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
?>
