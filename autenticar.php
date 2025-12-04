<?php
session_start();
require_once "conexao.php";

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email='$email'";
$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {

    if (password_verify($senha, $row['senha'])) {

        $_SESSION['usuario'] = $row['nome'];
        $_SESSION['id'] = $row['id'];

        header("Location: dashboard.php");
        exit;
    }
}

echo "<script>alert('Login inválido!');location.href='login.php';</script>";
?>
