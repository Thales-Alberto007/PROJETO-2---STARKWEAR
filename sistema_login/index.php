<?php
require "conexao.php";
session_start();

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user'] = $user['nome'];
            header("Location: dashboard.php");
            exit;
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Email não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #4a90e2, #007bff);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.container {
    background: white;
    padding: 30px;
    width: 350px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
input {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: white;
    border: none;
    margin-top: 15px;
    border-radius: 6px;
    cursor: pointer;
}
a { color: #007bff; text-decoration: none; }
.erro { margin-top: 10px; color: red; }
</style>

</head>
<body>

<div class="container">
    <h2>Login</h2>

    <?php if($erro): ?>
    <p class="erro"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Seu email" required>
        <input type="password" name="senha" placeholder="Sua senha" required>
        <button type="submit">Entrar</button>
    </form>

    <p style="margin-top:15px">
        Ainda não tem conta? <a href="register.php">Registrar</a>
    </p>
</div>

</body>
</html>
