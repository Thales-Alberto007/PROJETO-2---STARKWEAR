<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sistema_login";

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_errno) {
    die("Erro ao conectar: " . $con->connect_error);
}
?>
