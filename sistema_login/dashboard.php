<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
</head>
<body>

<h2>Bem-vindo, <?= $_SESSION['user'] ?></h2>
<p>Aqui futuramente terá sua tabela admin.</p>

<a href="logout.php">Sair</a>

</body>
</html>
