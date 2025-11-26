<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "loja";
$port = 3307;

$con = new mysqli($host, $user, $pass, $db, $port);

if ($con->connect_errno) {
    die("Erro na conexão: " . $con->connect_error);
}

$con->set_charset("utf8mb4");
?>
