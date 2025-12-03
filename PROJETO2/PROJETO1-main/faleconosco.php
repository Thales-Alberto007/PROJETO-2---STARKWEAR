<?php
require_once "admin/config.inc.php"; // Inclui a conexão com o banco de dados

$mensagem_newsletter = "";
$mensagem_newsletter_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subscribe_newsletter"])) {
    $email_newsletter = $_POST["email_newsletter"] ?? '';

    if (!filter_var($email_newsletter, FILTER_VALIDATE_EMAIL)) {
        $mensagem_newsletter = "Formato de e-mail inválido.";
        $mensagem_newsletter_type = "error";
    } else {
        // Verifica se o e-mail já está inscrito
        $stmt_check = mysqli_prepare($conexao, "SELECT id FROM newsletter_subscribers WHERE email = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $email_newsletter);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $mensagem_newsletter = "Este e-mail já está inscrito na nossa newsletter.";
            $mensagem_newsletter_type = "warn";
        } else {
            $stmt_insert = mysqli_prepare($conexao, "INSERT INTO newsletter_subscribers (email) VALUES (?)");
            mysqli_stmt_bind_param($stmt_insert, "s", $email_newsletter);

            if (mysqli_stmt_execute($stmt_insert)) {
                $mensagem_newsletter = "Obrigado por se inscrever na nossa newsletter!";
                $mensagem_newsletter_type = "success";
            } else {
                $mensagem_newsletter = "Erro ao inscrever: " . mysqli_error($conexao);
                $mensagem_newsletter_type = "error";
            }
            mysqli_stmt_close($stmt_insert);
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
    <title>Fale Conosco - StarkWear</title>
    <link rel="stylesheet" href="style.css"> <!-- Caminho correto para o CSS principal -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
            padding: 2rem 0;
        }
        .contact-page-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
            text-align: center;
        }
        h1 {
            color: var(--azul-marinho);
            margin-bottom: 1.5rem;
        }
        h2 {
            color: var(--fundo-mar);
            margin-top: 2rem;
            margin-bottom: 1.5rem;
        }
        p {
            color: rgba(0,0,0,0.8);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .contact-info {
            margin-top: 2rem;
            text-align: left;
            line-height: 1.8;
            color: rgba(0,0,0,0.85);
        }
        .contact-info strong {
            color: var(--azul-marinho);
        }
        .newsletter-section {
            margin-top: 3rem;
            border-top: 1px solid var(--coral-mar-cinzento);
            padding-top: 2rem;
        }
        .newsletter-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .newsletter-form input[type="email"] {
            flex: 1 1 250px;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(10,10,10,0.06);
            background: linear-gradient(180deg, rgba(255,255,255,0.8), rgba(255,255,255,0.7));
            box-shadow: inset 0 -2px 6px rgba(0,0,0,0.03);
            outline: none;
        }
        .newsletter-form button {
            padding: 0.7rem 1.5rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: var(--white);
            box-shadow: var(--shadow-1);
            transition: all var(--transition);
        }
        .newsletter-form button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-2);
        }
        .message-box {
            padding: 0.8rem;
            margin-top: 1rem;
            border-radius: var(--radius-sm);
            font-weight: bold;
            text-align: center;
        }
        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message-box.warn {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>
    <?php include_once "topo.php"; ?>

    <div class="container">
        <div class="contact-page-container">
            <h1>Fale Conosco</h1>
            <p>Tem alguma dúvida, sugestão ou precisa de ajuda? Entre em contato conosco!</p>

            <div class="contact-info">
                <p><strong>E-mail:</strong> contato@starkwear.com.br</p>
                <p><strong>Telefone:</strong> +55 (083) 9 8231-0455</p>
                <p><strong>Endereço:</strong> Rua da Praia, 157 - Centro, João Pessoa - Paraíba</p>
                <p><strong>Horário de Atendimento:</strong> Segunda a Sexta, das 9h às 18h</p>
            </div>

            <div class="newsletter-section">
                <h2>Assine nossa Newsletter!</h2>
                <p>Receba as últimas novidades, promoções exclusivas e lançamentos de coleções diretamente na sua caixa de entrada.</p>

                <?php if (!empty($mensagem_newsletter)): ?>
                    <div class="message-box <?= $mensagem_newsletter_type ?>">
                        <?= htmlspecialchars($mensagem_newsletter) ?>
                    </div>
                <?php endif; ?>

                <form action="?pg=faleconosco" method="post" class="newsletter-form">
                    <input type="email" name="email_newsletter" placeholder="Digite seu e-mail" required>
                    <button type="submit" name="subscribe_newsletter">Inscrever-se</button>
                </form>
            </div>
        </div>
    </div>

    <?php include_once "rodape.php"; ?>
</body>
</html>
