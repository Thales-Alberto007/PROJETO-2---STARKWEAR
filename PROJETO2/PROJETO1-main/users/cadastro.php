<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"] ?? '';
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
            $mensagem = "Este e-mail já está cadastrado. Faça login ou use outro e-mail.";
        } else {
            // Criptografa a senha antes de salvar
            $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

            // Prepared Statement para inserir o novo cliente
            $stmt = mysqli_prepare($conexao, "INSERT INTO users (nome, email, senha, cidade, estado) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $nome, $email, $senha_hashed, $cidade, $estado);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Cadastro realizado com sucesso! Você já pode fazer login.";
                // Redireciona para a página de login após o cadastro
                header("Location: " . FULL_ROUTER_URL . "?pg=users/login&cadastro_sucesso=true");
                exit();
            } else {
                $mensagem = "Erro ao cadastrar: " . mysqli_error($conexao);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($stmt_check);
    }
}
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - StarkWear</title>
    <link rel="stylesheet" href="../style.css"> <!-- Caminho correto para o CSS principal -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Garante que o body ocupa toda a altura da viewport */
        }
        .container {
            flex: 1; /* Permite que o container se expanda e ocupe o espaço disponível */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
        }
        .card-form {
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            padding: 2.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: var(--azul-marinho);
            margin-bottom: 1.5rem;
        }
        .message {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: var(--radius-sm);
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
        .form-row {
            margin-bottom: 1rem;
            text-align: left;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.35rem;
            color: var(--fundo-mar);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(10,10,10,0.06);
            background: linear-gradient(180deg, rgba(255,255,255,0.8), rgba(255,255,255,0.7));
            box-shadow: inset 0 -2px 6px rgba(0,0,0,0.03);
            outline: none;
        }
        button {
            width: 100%;
            padding: 0.8rem 1.2rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition);
            box-shadow: var(--shadow-1);
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: var(--white);
            font-size: 1.1rem;
            margin-top: 1.5rem;
        }
        button:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: var(--shadow-2);
        }
        .link-text {
            margin-top: 1rem;
            font-size: 0.9em;
            color: var(--muted);
        }
        .link-text a {
            color: var(--azul-do-mar);
            text-decoration: none;
            font-weight: bold;
        }
        .link-text a:hover {
            color: var(--fundo-mar);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../topo.php"; // Inclui o topo para o estilo ?>

    <div class="container">
        <div class="card-form">
            <h2>Cadastre-se para comprar!</h2>
            <?php if (!empty($mensagem)): ?>
                <p class="message <?= (strpos($mensagem, 'sucesso') !== false) ? 'success' : (strpos($mensagem, 'cadastrado') !== false ? 'warn' : 'error') ?>">
                    <?= htmlspecialchars($mensagem) ?></p>
            <?php endif; ?>
            <form action="<?= ROUTER_URL ?>?pg=users/cadastro" method="post">
                <div class="form-row">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="form-row">
                    <label for="cidade">Cidade (Opcional):</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <label for="estado">Estado (Opcional):</label>
                    <input type="text" id="estado" name="estado" value="<?= htmlspecialchars($_POST['estado'] ?? '') ?>">
                </div>
                <button type="submit">Cadastrar</button>
            </form>
            <p class="link-text">Já tem uma conta? <a href="<?= ROUTER_URL ?>?pg=users/login">Faça login aqui!</a></p>
        </div>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
