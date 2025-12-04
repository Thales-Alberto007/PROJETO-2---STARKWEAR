<?php
require_once "conexao.php";

$id = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id=$id";
mysqli_query($con, $sql);

echo "<script>alert('Usuário excluído!');location.href='usuarios_listar.php';</script>";
