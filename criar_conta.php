<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="style_criar.css">
</head>
<body>

<h2>Criar Conta</h2>

<div class="form-container">
    <h2>Criar Conta</h2>
    <form action="usuarios_inserir.php" method="post" class="form">
        <label>Nome</label>
        <input type="text" name="nome" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="senha" required>

        <button type="submit">Cadastrar</button>
    </form>
</div>
