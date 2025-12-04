<?php
session_start();

$nome = $_SESSION['usuario'] ?? "Usuário";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
    <link rel="stylesheet" href="style_dashboard.css">
</head>

<body>

<div class="box">

    <h2>Bem-vindo, <?= $nome ?>!</h2>

    <a href="usuarios_listar.php">Gerenciar Usuários</a>

    <a href="logout.php" class="logout">Sair</a>
</div>

</body>
</html>
