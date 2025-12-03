<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once dirname(__DIR__) . "/admin/config.inc.php";

$mensagem = $_GET['message'] ?? '';
$mensagem_type = $_GET['message_type'] ?? '';

$colecao_filtro = $_GET['colecao'] ?? '';

$sql_produtos = "SELECT id, nome, preco, imagem, desconto, colecao FROM produtos";
$params = [];
$types = "";

if (!empty($colecao_filtro)) {
    $sql_produtos .= " WHERE colecao = ?";
    $params[] = $colecao_filtro;
    $types .= "s";
}

$produtos = [];
$stmt = mysqli_prepare($conexao, $sql_produtos);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($resultado)) {
    $produtos[] = $row;
}
mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nossas Coleções - StarkWear</title>
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
        .products-page-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
        }
        h1 {
            color: var(--azul-marinho);
            margin-bottom: 1.5rem;
            text-align: center;
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
        .collection-filters {
            text-align: center;
            margin-bottom: 2rem;
        }
        .collection-filters a {
            display: inline-block;
            padding: 0.7rem 1.2rem;
            margin: 0 0.5rem 0.5rem 0.5rem;
            background: var(--areia-1);
            color: var(--azul-marinho);
            text-decoration: none;
            border-radius: 999px;
            font-weight: 600;
            transition: all var(--transition);
            box-shadow: var(--shadow-0);
        }
        .collection-filters a:hover {
            background: var(--areia-2);
            transform: translateY(-2px);
            box-shadow: var(--shadow-1);
        }
        .collection-filters a.active {
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: var(--white);
            box-shadow: var(--shadow-1);
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .product-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-1);
            overflow: hidden;
            transition: all var(--transition);
            text-align: center;
            padding-bottom: 1.5rem;
        }
        .product-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-2);
        }
        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 1px solid var(--coral-mar-cinzento);
            margin-bottom: 1rem;
        }
        .product-card h3 {
            color: var(--azul-marinho);
            font-size: 1.4rem;
            margin: 0 1rem 0.5rem 1rem;
        }
        .product-card .price {
            font-size: 1.3rem;
            color: var(--fundo-mar);
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .product-card .old-price {
            text-decoration: line-through;
            color: var(--muted);
            font-size: 0.9em;
            margin-right: 5px;
        }
        .product-card .add-to-cart-btn {
            display: inline-block;
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: 999px;
            text-decoration: none;
            margin-top: 1rem;
            transition: all var(--transition);
            box-shadow: var(--shadow-0);
        }
        .product-card .add-to-cart-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-1);
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../topo.php"; ?>

    <div class="container">
        <div class="products-page-container">
            <h1>Nossas Coleções StarkWear</h1>

            <?php if (!empty($mensagem)): ?>
                <div class="message-box <?= $mensagem_type ?>">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>

            <div class="collection-filters">
                <a href="?pg=produtos/index" class="<?= empty($colecao_filtro) ? 'active' : '' ?>">Todas as Coleções</a>
                <?php
                $colecoes = array_unique(array_column($produtos, 'colecao'));
                foreach ($colecoes as $colecao): ?>
                    <a href="?pg=produtos/index&colecao=<?= urlencode($colecao) ?>" class="<?= ($colecao_filtro == $colecao) ? 'active' : '' ?>">
                        <?= htmlspecialchars($colecao) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="product-grid">
                <?php if (empty($produtos)): ?>
                    <p style="grid-column: 1 / -1; text-align: center;">Nenhum produto encontrado para esta coleção.</p>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <?php
                        $preco_final = $produto['preco'] * (1 - $produto['desconto']);
                        ?>
                        <div class="product-card">
                            <a href="<?= ROUTER_URL ?>?pg=produtos/detalhes&id=<?= htmlspecialchars($produto['id']) ?>">
                                <?php
                                $image_src = strpos($produto['imagem'], 'http') === 0 ? $produto['imagem'] : BASE_URL . '/' . $produto['imagem'];
                                ?>
                                <img src="<?= htmlspecialchars($image_src) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                            </a>
                            <p class="price">
                                <?php if ($produto['desconto'] > 0): ?>
                                    <span class="old-price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                                <?php endif; ?>
                                R$ <?= number_format($preco_final, 2, ',', '.') ?>
                            </p>
                            <form action="<?= ROUTER_URL ?>?pg=carrinho/add" method="post">
                                <input type="hidden" name="produto_id" value="<?= htmlspecialchars($produto['id']) ?>">
                                <input type="hidden" name="quantidade" value="1"> <!-- Adiciona 1 por padrão -->
                                <button type="submit" class="add-to-cart-btn">Adicionar ao Carrinho</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; ?>
</body>
</html>
