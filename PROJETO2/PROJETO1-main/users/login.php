<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

$mensagem = "";

if (isset($_GET['cadastro_sucesso']) && $_GET['cadastro_sucesso'] == 'true') {
    $mensagem = "Cadastro realizado com sucesso! Faça login para continuar.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $senha = $_POST["senha"] ?? '';

    // Validação básica
    if (empty($email) || empty($senha)) {
        $mensagem = "Por favor, preencha todos os campos.";
    } else {
        // Prepared Statement para buscar o usuário
        $stmt = mysqli_prepare($conexao, "SELECT id, nome, senha FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($resultado)) {
            // Verifica a senha
            if (password_verify($senha, $user['senha'])) {
                $_SESSION["user_logged_in"] = true;
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["user_nome"] = $user['nome'];
                header("Location: " . FULL_ROUTER_URL . "?pg=conteudo"); // Redireciona para a página inicial do cliente
                exit();
            } else {
                $mensagem = "E-mail ou senha inválidos.";
            }
        } else {
            $mensagem = "E-mail ou senha inválidos.";
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Cliente - StarkWear</title>
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
            <h2>Faça seu Login!</h2>
            <?php if (!empty($mensagem)): ?>
                <p class="message <?= (strpos($mensagem, 'sucesso') !== false) ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($mensagem) ?></p>
            <?php endif; ?>
            <form action="<?= ROUTER_URL ?>?pg=users/login" method="post">
                <div class="form-row">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit">Entrar</button>
            </form>
            <p class="link-text">Não tem uma conta? <a href="<?= ROUTER_URL ?>?pg=users/cadastro">Cadastre-se aqui!</a></p>
        </div>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
