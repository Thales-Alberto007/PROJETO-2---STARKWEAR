<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "sistema_login";

$con = mysqli_connect($servidor, $usuario, $senha, $banco);

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}
?>
