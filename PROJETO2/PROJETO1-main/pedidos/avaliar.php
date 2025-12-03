<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: " . FULL_ROUTER_URL . "?pg=users/login&message=Por favor, faça login para avaliar produtos.");
    exit();
}

$user_id = $_SESSION["user_id"];
$user_nome = $_SESSION["user_nome"];
$pedido_id = $_GET['pedido_id'] ?? '';
$message = $_GET['message'] ?? '';
$message_type = $_GET['message_type'] ?? '';

$produtos_no_pedido = [];
$avaliacao_sucesso = false;

// Se o pedido_id for fornecido, busca os produtos associados a ele
if (!empty($pedido_id)) {
    $stmt_produtos_pedido = mysqli_prepare($conexao, "SELECT dp.produto_id, p.nome FROM detalhes_pedido dp JOIN produtos p ON dp.produto_id = p.id WHERE dp.pedido_id = ?");
    mysqli_stmt_bind_param($stmt_produtos_pedido, "i", $pedido_id);
    mysqli_stmt_execute($stmt_produtos_pedido);
    $resultado_produtos_pedido = mysqli_stmt_get_result($stmt_produtos_pedido);
    while ($row = mysqli_fetch_assoc($resultado_produtos_pedido)) {
        $produtos_no_pedido[] = $row;
    }
    mysqli_stmt_close($stmt_produtos_pedido);
} else {
    $message = "Nenhum pedido especificado para avaliação.";
    $message_type = "error";
}

// Lógica para processar a avaliação
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id_avaliacao = $_POST["produto_id"] ?? '';
    $nota = $_POST["nota"] ?? '';
    $comentario = $_POST["comentario"] ?? '';

    if (empty($produto_id_avaliacao) || empty($nota) || $nota < 1 || $nota > 5) {
        $message = "Por favor, selecione um produto e dê uma nota de 1 a 5.";
        $message_type = "error";
    } else {
        $stmt_avaliacao = mysqli_prepare($conexao, "INSERT INTO avaliacoes (produto_id, user_id, nota, comentario) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_avaliacao, "iiis", $produto_id_avaliacao, $user_id, $nota, $comentario);

        if (mysqli_stmt_execute($stmt_avaliacao)) {
            $message = "Obrigado por avaliar nosso serviço, esperamos que você tenha gostado da experiência na loja StarkWear, volte sempre!";
            $message_type = "success";
            $avaliacao_sucesso = true;
        } else {
            $message = "Erro ao salvar sua avaliação: " . mysqli_error($conexao);
            $message_type = "error";
        }
        mysqli_stmt_close($stmt_avaliacao);
    }
}
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avalie sua Compra - StarkWear</title>
    <link rel="stylesheet" href="../style.css">
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
        .review-container {
            max-width: 600px;
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
        .message-box {
            padding: 0.8rem;
            margin-bottom: 1rem;
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
        select, textarea {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(10,10,10,0.06);
            background: linear-gradient(180deg, rgba(255,255,255,0.8), rgba(255,255,255,0.7));
            box-shadow: inset 0 -2px 6px rgba(0,0,0,0.03);
            outline: none;
        }
        .star-rating-input {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .star-rating-input label {
            display: inline-block;
            width: 30px;
            height: 30px;
            font-size: 24px;
            line-height: 30px;
            cursor: pointer;
            color: #ccc; /* Cor das estrelas vazias */
        }
        .star-rating-input input[type="radio"] {
            display: none;
        }
        .star-rating-input input[type="radio"]:checked ~ label,
        .star-rating-input label:hover,
        .star-rating-input label:hover ~ label {
            color: #ffc107; /* Cor das estrelas preenchidas */
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
        .back-home-button {
            margin-top: 1rem;
            padding: 0.8rem 1.5rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            background: linear-gradient(90deg, var(--areia), var(--areia-1));
            color: var(--azul-marinho);
            box-shadow: var(--shadow-1);
            transition: all var(--transition);
            text-decoration: none;
            display: inline-block;
        }
        .back-home-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-2);
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../topo.php"; ?>

    <div class="container">
        <h1>Avalie sua Experiência!</h1>

        <?php if (!empty($message)): ?>
            <div class="message-box <?= $message_type ?>">
                <?= htmlspecialchars($message) ?><br>
                <?php if ($avaliacao_sucesso): ?>
                    <a href="<?= FULL_ROUTER_URL ?>?pg=conteudo" class="back-home-button">Voltar para a Página Inicial</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!$avaliacao_sucesso && !empty($produtos_no_pedido)): ?>
            <div class="review-container">
                <form action="<?= FULL_ROUTER_URL ?>?pg=pedidos/avaliar&pedido_id=<?= htmlspecialchars($pedido_id) ?>" method="post">
                    <div class="form-row">
                        <label for="produto_id">Produto Avaliado:</label>
                        <select id="produto_id" name="produto_id" required>
                            <option value="">Selecione um produto</option>
                            <?php foreach ($produtos_no_pedido as $produto): ?>
                                <option value="<?= htmlspecialchars($produto['produto_id']) ?>"><?= htmlspecialchars($produto['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <label>Sua Nota:</label>
                        <div class="star-rating-input">
                            <input type="radio" id="star5" name="nota" value="5" required><label for="star5">&#9733;</label>
                            <input type="radio" id="star4" name="nota" value="4"><label for="star4">&#9733;</label>
                            <input type="radio" id="star3" name="nota" value="3"><label for="star3">&#9733;</label>
                            <input type="radio" id="star2" name="nota" value="2"><label for="star2">&#9733;</label>
                            <input type="radio" id="star1" name="nota" value="1"><label for="star1">&#9733;</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="comentario">Comentário (Opcional):</label>
                        <textarea id="comentario" name="comentario" rows="5" placeholder="Conte-nos sobre sua experiência..."></textarea>
                    </div>

                    <button type="submit">Enviar Avaliação</button>
                </form>
            </div>
        <?php elseif (!$avaliacao_sucesso && empty($produtos_no_pedido)): ?>
             <div class="review-container">
                <h3>Não há produtos neste pedido para avaliar ou o pedido não foi encontrado.</h3>
                <p class="text-center"><a href="<?= FULL_ROUTER_URL ?>?pg=conteudo" class="back-home-button">Voltar para a Página Inicial</a></p>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; ?>
</body>
</html>
