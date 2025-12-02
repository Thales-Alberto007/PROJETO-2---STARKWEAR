<?php
require 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $res = $con->query($sql);

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
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
<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Login</h2>

    <?php if(isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Digite seu email" required>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit">Entrar</button>
    </form>

    <a href="registrar.php">Criar uma conta</a>
</div>
