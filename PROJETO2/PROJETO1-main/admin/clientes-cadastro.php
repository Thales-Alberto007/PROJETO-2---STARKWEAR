<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "config.inc.php";

$mensagem = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST["cliente"] ?? ''; // Nome do cliente será o campo 'nome' na tabela users
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';
    $cidade = $_POST["cidade"] ?? '';
    $estado = $_POST["estado"] ?? '';

    // Validação básica
    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Formato de e-mail inválido.";
    } else {
        // Verifica se o e-mail já está cadastrado
        $stmt_check = mysqli_prepare($conexao, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $mensagem = "Este e-mail já está cadastrado.";
        } else {
            // Criptografa a senha antes de salvar
            $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

            // Prepared Statement para inserir o novo cliente na tabela 'users'
            $stmt = mysqli_prepare($conexao, "INSERT INTO users (nome, email, senha, cidade, estado) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $nome, $email, $senha_hashed, $cidade, $estado);

            if(mysqli_stmt_execute($stmt)){
                $mensagem = "Cliente cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar cliente: " . mysqli_error($conexao);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($stmt_check);
    }
    mysqli_close($conexao);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../style.css"> <!-- Caminho correto para o CSS principal -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container-admin {
            flex: 1;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message.warn {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        a.button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include_once "../topo.php"; // Inclui o topo para o estilo ?>

    <div class="container-admin">
        <h2>Status do Cadastro de Cliente</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="message <?= (strpos($mensagem, 'sucesso') !== false) ? 'success' : (strpos($mensagem, 'cadastrado') !== false ? 'warn' : 'error') ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>
        <a href="?pg=clientes-admin" class="button">Voltar para Clientes</a>
    </div>

    <?php include_once "../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
