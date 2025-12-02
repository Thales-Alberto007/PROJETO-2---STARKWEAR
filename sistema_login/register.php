<?php
require 'conexao.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verificar email repetido
    $check = $con->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "❌ Este e-mail já está cadastrado!";
    } else {
        $sql = $con->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nome, $email, $senha);

        if ($sql->execute()) {
            $msg = "✔ Usuário registrado com sucesso!";
        } else {
            $msg = "❌ Erro ao registrar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Registrar</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h2>Registrar</h2>

<!-- 🔥 PASSO 2 VAI AQUI, ANTES DO FORM 🔥 -->
<?php if (!empty($msg)): ?>
    <div style="background:#ffdddd; padding:10px; border-left:4px solid red; margin-bottom:15px;">
        <?= $msg ?>
    </div>
<?php endif; ?>
<!-- 🔥 FIM DO PASSO 2 -->

<form method="POST" action="register.php">
    <input type="text" name="nome" placeholder="Seu nome" required>
    <input type="email" name="email" placeholder="Seu e-mail" required>
    <input type="password" name="senha" placeholder="Sua senha" required>

    <button type="submit">Cadastrar</button>
</form>

<a href="index.php">← Voltar ao login</a>

</div>

</body>
</html>
