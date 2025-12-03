<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../?pg=users/login&message=Por favor, faça login para ver seu carrinho.");
    exit();
}

$user_id = $_SESSION["user_id"];
$mensagem = "";
$mensagem_type = "";

// Lógica para atualizar a quantidade ou remover item do carrinho
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_quantidade"])) {
        $carrinho_item_id = $_POST["carrinho_item_id"] ?? '';
        $nova_quantidade = $_POST["quantidade"] ?? 1;

        $nova_quantidade = max(1, (int)$nova_quantidade);

        if (!empty($carrinho_item_id)) {
            $stmt = mysqli_prepare($conexao, "UPDATE carrinho SET quantidade = ? WHERE id = ? AND user_id = ?");
            mysqli_stmt_bind_param($stmt, "iii", $nova_quantidade, $carrinho_item_id, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Quantidade atualizada com sucesso!";
                $mensagem_type = "success";
            } else {
                $mensagem = "Erro ao atualizar quantidade: " . mysqli_error($conexao);
                $mensagem_type = "error";
            }
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST["remover_item"])) {
        $carrinho_item_id = $_POST["carrinho_item_id"] ?? '';
        if (!empty($carrinho_item_id)) {
            $stmt = mysqli_prepare($conexao, "DELETE FROM carrinho WHERE id = ? AND user_id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $carrinho_item_id, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Item removido do carrinho!";
                $mensagem_type = "success";
            } else {
                $mensagem = "Erro ao remover item: " . mysqli_error($conexao);
                $mensagem_type = "error";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Busca os itens do carrinho do usuário
$itens_carrinho = [];
$total_carrinho = 0;

$sql_carrinho = "SELECT c.id as carrinho_item_id, c.quantidade, p.id as produto_id, p.nome, p.preco, p.imagem, p.desconto FROM carrinho c JOIN produtos p ON c.produto_id = p.id WHERE c.user_id = ?";
$stmt_carrinho = mysqli_prepare($conexao, $sql_carrinho);
mysqli_stmt_bind_param($stmt_carrinho, "i", $user_id);
mysqli_stmt_execute($stmt_carrinho);
$resultado_carrinho = mysqli_stmt_get_result($stmt_carrinho);

while ($row = mysqli_fetch_assoc($resultado_carrinho)) {
    $preco_unitario_com_desconto = $row['preco'] * (1 - $row['desconto']);
    $subtotal_item = $preco_unitario_com_desconto * $row['quantidade'];
    $total_carrinho += $subtotal_item;
    $itens_carrinho[] = [
        'carrinho_item_id' => htmlspecialchars($row['carrinho_item_id']),
        'produto_id' => htmlspecialchars($row['produto_id']),
        'nome' => htmlspecialchars($row['nome']),
        'preco_original' => htmlspecialchars($row['preco']),
        'desconto' => htmlspecialchars($row['desconto']),
        'preco_final_unitario' => number_format($preco_unitario_com_desconto, 2, ',', '.'),
        'quantidade' => htmlspecialchars($row['quantidade']),
        'subtotal' => number_format($subtotal_item, 2, ',', '.'),
        'imagem' => htmlspecialchars($row['imagem'])
    ];
}
mysqli_stmt_close($stmt_carrinho);
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - StarkWear</title>
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
        .cart-page-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
        }
        h2 {
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
        .cart-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-bottom: 1px solid var(--coral-mar-cinzento);
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-1);
        }
        .item-details {
            flex-grow: 1;
            text-align: left;
            color: rgba(0,0,0,0.82);
        }
        .item-details h3 {
            margin: 0 0 0.5rem 0;
            color: var(--azul-marinho);
            font-size: 1.2rem;
        }
        .item-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .item-actions input[type="number"] {
            width: 70px;
            text-align: center;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(10,10,10,0.06);
            background: rgba(255,255,255,0.8);
        }
        .item-actions button {
            background: none;
            border: none;
            color: var(--fundo-mar);
            cursor: pointer;
            font-weight: bold;
            transition: color 0.2s;
            padding: 0.5rem;
        }
        .item-actions button:hover {
            color: var(--azul-do-mar);
        }
        .cart-summary {
            background: var(--areia-1);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-1);
            margin-top: 2rem;
            text-align: right;
            color: var(--azul-marinho);
        }
        .cart-summary h3 {
            color: var(--azul-marinho);
            margin-bottom: 1rem;
        }
        .cart-summary .total {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--azul-marinho);
            margin-bottom: 1.5rem;
        }
        .checkout-btn {
            width: auto;
            margin-left: auto;
            padding: 0.8rem 1.5rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: var(--white);
            box-shadow: var(--shadow-1);
            transition: all var(--transition);
            text-decoration: none;
            display: inline-block;
        }
        .checkout-btn:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: var(--shadow-2);
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../topo.php"; ?>

    <div class="container">
        <h2>Seu Carrinho de Compras</h2>

        <?php if (!empty($mensagem)): ?>
            <div class="message-box <?= $mensagem_type ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($itens_carrinho)): ?>
            <div class="cart-page-container">
                <h3 class="text-center">Seu carrinho está vazio.</h3>
                <p class="text-center"><a href="../?pg=produtos/index" class="checkout-btn">Ver Produtos</a></p>
            </div>
        <?php else: ?>
            <div class="cart-page-container">
                <?php foreach ($itens_carrinho as $item): ?>
                    <div class="cart-item">
                        <img src="../<?= $item['imagem'] ?>" alt="<?= $item['nome'] ?>">
                        <div class="item-details">
                            <h3><?= $item['nome'] ?></h3>
                            <p>Preço Unitário: R$ <?= $item['preco_final_unitario'] ?></p>
                            <?php if ($item['desconto'] > 0): ?>
                                <p class="small" style="color: var(--azul-do-mar);">Economia de <?= round($item['desconto'] * 100) ?>%</p>
                            <?php endif; ?>
                            <p>Subtotal: R$ <?= $item['subtotal'] ?></p>
                        </div>
                        <div class="item-actions">
                            <form action="?pg=carrinho/index" method="post" style="display:flex; align-items:center; gap: 0.5rem;">
                                <input type="hidden" name="carrinho_item_id" value="<?= $item['carrinho_item_id'] ?>">
                                <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1">
                                <button type="submit" name="update_quantidade">Atualizar</button>
                            </form>
                            <form action="?pg=carrinho/index" method="post">
                                <input type="hidden" name="carrinho_item_id" value="<?= $item['carrinho_item_id'] ?>">
                                <button type="submit" name="remover_item" style="color: red;">Remover</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="cart-summary">
                    <h3>Total do Carrinho:</h3>
                    <p class="total">R$ <?= number_format($total_carrinho, 2, ',', '.') ?></p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                        <a href="<?= ROUTER_URL ?>?pg=produtos/index" class="checkout-btn" style="background: var(--azul-marinho);">Continuar Comprando</a>
                        <a href="<?= ROUTER_URL ?>?pg=pedidos/checkout" class="checkout-btn">Finalizar Compra</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; ?>
</body>
</html>
