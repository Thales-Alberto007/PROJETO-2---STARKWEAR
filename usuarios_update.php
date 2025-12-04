<?php
require_once "conexao.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];

$sql = "UPDATE usuarios SET nome='$nome', email='$email' WHERE id=$id";

mysqli_query($con, $sql);

echo "<script>alert('Atualizado com sucesso!');location.href='usuarios_listar.php';</script>";
