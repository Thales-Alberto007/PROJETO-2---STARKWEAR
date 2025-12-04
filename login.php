<?php session_start([
    'cookie_lifetime' => 0
]); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">

    <h2>Login</h2>

    <form action="autenticar.php" method="post" class="form">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="senha" required>

        <button type="submit">Entrar</button>
    </form>

    <p>Ainda não tem conta? <a href="criar_conta.php">Criar conta</a></p>

</div>

</body>
</html>
