<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

$produto_id = $_GET['id'] ?? '';
$produto = null;
$avaliacoes = [];

if (!empty($produto_id)) {
    // Buscar detalhes do produto
    $stmt_produto = mysqli_prepare($conexao, "SELECT id, nome, preco, descricao, imagem, desconto, colecao FROM produtos WHERE id = ?");
    mysqli_stmt_bind_param($stmt_produto, "i", $produto_id);
    mysqli_stmt_execute($stmt_produto);
    $resultado_produto = mysqli_stmt_get_result($stmt_produto);
    if (mysqli_num_rows($resultado_produto) > 0) {
        $produto = mysqli_fetch_assoc($resultado_produto);
    } else {
        echo "<p style=\"color:red; text-align:center;\">Produto não encontrado!</p>";
        exit();
    }
    mysqli_stmt_close($stmt_produto);

    // Buscar avaliações do produto
    $stmt_avaliacoes = mysqli_prepare($conexao, "SELECT a.nota, a.comentario, u.nome as user_nome, a.data_avaliacao FROM avaliacoes a JOIN users u ON a.user_id = u.id WHERE a.produto_id = ? ORDER BY a.data_avaliacao DESC");
    mysqli_stmt_bind_param($stmt_avaliacoes, "i", $produto_id);
    mysqli_stmt_execute($stmt_avaliacoes);
    $resultado_avaliacoes = mysqli_stmt_get_result($stmt_avaliacoes);
    while ($row = mysqli_fetch_assoc($resultado_avaliacoes)) {
        $avaliacoes[] = $row;
    }
    mysqli_stmt_close($stmt_avaliacoes);
}
mysqli_close($conexao);

if (!$produto) {
    header("Location: ../?pg=produtos/index&message_type=error&message=Produto não encontrado.");
    exit();
}

$preco_final = $produto['preco'] * (1 - $produto['desconto']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome']) ?> - StarkWear</title>
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
        .product-detail-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }
        .product-image-area {
            flex: 1 1 40%;
            min-width: 300px;
            text-align: center;
        }
        .product-image-area img {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-1);
        }
        .product-info-area {
            flex: 1 1 50%;
            min-width: 300px;
        }
        h1 {
            color: var(--azul-marinho);
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .price-section {
            margin-bottom: 1.5rem;
            color: var(--fundo-mar);
        }
        .price-section .current-price {
            font-size: 2.2rem;
            font-weight: bold;
            margin-right: 10px;
        }
        .price-section .old-price {
            text-decoration: line-through;
            color: var(--muted);
            font-size: 1.2rem;
        }
        .price-section .discount-tag {
            background-color: var(--verde-mar);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: var(--radius-sm);
            font-size: 0.9em;
            margin-left: 10px;
            font-weight: bold;
        }
        .product-description {
            color: rgba(0,0,0,0.8);
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .add-to-cart-form {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .add-to-cart-form input[type="number"] {
            width: 80px;
            padding: 0.6rem 0.8rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(10,10,10,0.06);
            background: rgba(255,255,255,0.8);
            text-align: center;
            font-size: 1.1rem;
        }
        .add-to-cart-form button {
            padding: 0.8rem 1.5rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: var(--white);
            box-shadow: var(--shadow-1);
            transition: all var(--transition);
            font-size: 1.1rem;
        }
        .add-to-cart-form button:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: var(--shadow-2);
        }
        .reviews-section {
            width: 100%;
            margin-top: 3rem;
            border-top: 1px solid var(--coral-mar-cinzento);
            padding-top: 2rem;
        }
        .reviews-section h2 {
            text-align: center;
            margin-bottom: 2rem;
        }
        .review-item {
            background: var(--areia-1);
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-0);
        }
        .review-item .rating {
            color: #ffc107;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        .review-item .reviewer-info {
            font-size: 0.9em;
            color: var(--muted);
            margin-bottom: 0.8rem;
        }
        .review-item .comment {
            color: rgba(0,0,0,0.85);
            line-height: 1.5;
        }
        .star-filled { color: #ffc107; }
        .star-empty { color: #ccc; }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-detail-container {
                flex-direction: column;
                align-items: center;
            }
            .product-image-area, .product-info-area {
                min-width: unset;
                width: 100%;
            }
            .add-to-cart-form {
                flex-direction: column;
                align-items: stretch;
            }
            .add-to-cart-form input[type="number"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../topo.php"; ?>

    <div class="container">
        <div class="product-detail-container">
            <div class="product-image-area">
                <?php
                $image_src = strpos($produto['imagem'], 'http') === 0 ? $produto['imagem'] : BASE_URL . '/' . $produto['imagem'];
                ?>
                <img src="<?= htmlspecialchars($image_src) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
            </div>
            <div class="product-info-area">
                <h1><?= htmlspecialchars($produto['nome']) ?></h1>
                <?php if (!empty($produto['colecao'])): ?>
                    <p>Coleção: <strong><?= htmlspecialchars($produto['colecao']) ?></strong></p>
                <?php endif; ?>

                <?php if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true): ?>
                    <p>Você está logado como: <strong><?= htmlspecialchars($_SESSION['user_nome']) ?></strong></p>
                <?php endif; ?>
                <?php echo "DEBUG: BASE_URL é: " . BASE_URL . "<br>"; ?>
                <div class="price-section">
                    <?php if ($produto['desconto'] > 0): ?>
                        <span class="old-price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                        <span class="current-price">R$ <?= number_format($preco_final, 2, ',', '.') ?></span>
                        <span class="discount-tag">-<?= round($produto['desconto'] * 100) ?>%</span>
                    <?php else: ?>
                        <span class="current-price">R$ <?= number_format($preco_final, 2, ',', '.') ?></span>
                    <?php endif; ?>
                </div>
                <p class="product-description"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>

                <form action="<?= ROUTER_URL ?>?pg=carrinho/add" method="post" class="add-to-cart-form">
                    <?php echo "DEBUG FORM ACTION: " . ROUTER_URL . "?pg=carrinho/add<br>"; // Linha de depuração temporária ?>
                    <input type="hidden" name="produto_id" value="<?= htmlspecialchars($produto['id']) ?>">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" name="quantidade" value="1" min="1">
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>

            <div class="reviews-section">
                <h2>Avaliações de Clientes</h2>
                <?php if (empty($avaliacoes)): ?>
                    <p style="text-align: center; color: var(--muted);">Nenhuma avaliação ainda para este produto. Seja o primeiro a avaliar!</p>
                <?php else: ?>
                    <?php foreach ($avaliacoes as $avaliacao): ?>
                        <div class="review-item">
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="<?= ($i <= $avaliacao['nota']) ? 'star-filled' : 'star-empty' ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <p class="reviewer-info">
                                Por: <?= htmlspecialchars($avaliacao['user_nome']) ?> em <?= date('d/m/Y H:i', strtotime($avaliacao['data_avaliacao'])) ?>
                            </p>
                            <?php if (!empty($avaliacao['comentario'])): ?>
                                <p class="comment"><?= nl2br(htmlspecialchars($avaliacao['comentario'])) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; ?>
</body>
</html>
