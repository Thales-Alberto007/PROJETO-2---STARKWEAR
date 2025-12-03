<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: " . FULL_ROUTER_URL . "?pg=users/login&message=Por favor, faça login para finalizar a compra.");
    exit();
}

$user_id = $_SESSION["user_id"];
$mensagem = "";
$mensagem_type = "";

// Lógica para finalizar a compra
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metodo_pagamento = $_POST["metodo_pagamento"] ?? '';

    // Validação básica do método de pagamento
    $allowed_payment_methods = ["boleto_bancario", "cartao_credito", "debito", "pix"];
    if (!in_array($metodo_pagamento, $allowed_payment_methods)) {
        $mensagem = "Método de pagamento inválido.";
        $mensagem_type = "error";
    } else {
        // Obter itens do carrinho
        $itens_carrinho = [];
        $total_carrinho = 0;

        $sql_carrinho = "SELECT c.quantidade, p.id as produto_id, p.nome, p.preco, p.desconto FROM carrinho c JOIN produtos p ON c.produto_id = p.id WHERE c.user_id = ?";
        $stmt_carrinho = mysqli_prepare($conexao, $sql_carrinho);
        mysqli_stmt_bind_param($stmt_carrinho, "i", $user_id);
        mysqli_stmt_execute($stmt_carrinho);
        $resultado_carrinho = mysqli_stmt_get_result($stmt_carrinho);

        if (mysqli_num_rows($resultado_carrinho) == 0) {
            $mensagem = "Seu carrinho está vazio. Adicione produtos antes de finalizar a compra.";
            $mensagem_type = "error";
        } else {
            while ($row = mysqli_fetch_assoc($resultado_carrinho)) {
                $preco_unitario_com_desconto = $row['preco'] * (1 - $row['desconto']);
                $subtotal_item = $preco_unitario_com_desconto * $row['quantidade'];
                $total_carrinho += $subtotal_item;
                $itens_carrinho[] = [
                    'produto_id' => $row['produto_id'],
                    'quantidade' => $row['quantidade'],
                    'preco_unitario' => $preco_unitario_com_desconto
                ];
            }
            mysqli_stmt_close($stmt_carrinho);

            // Inserir o pedido na tabela 'pedidos'
            $stmt_pedido = mysqli_prepare($conexao, "INSERT INTO pedidos (user_id, total_pedido, metodo_pagamento) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_pedido, "ids", $user_id, $total_carrinho, $metodo_pagamento);
            if (mysqli_stmt_execute($stmt_pedido)) {
                $pedido_id = mysqli_insert_id($conexao);
                mysqli_stmt_close($stmt_pedido);

                // Inserir os detalhes do pedido na tabela 'detalhes_pedido'
                $stmt_detalhes = mysqli_prepare($conexao, "INSERT INTO detalhes_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
                foreach ($itens_carrinho as $item) {
                    mysqli_stmt_bind_param($stmt_detalhes, "iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco_unitario']);
                    mysqli_stmt_execute($stmt_detalhes);
                }
                mysqli_stmt_close($stmt_detalhes);

                // Esvaziar o carrinho do usuário
                $stmt_esvaziar_carrinho = mysqli_prepare($conexao, "DELETE FROM carrinho WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt_esvaziar_carrinho, "i", $user_id);
                mysqli_stmt_execute($stmt_esvaziar_carrinho);
                mysqli_stmt_close($stmt_esvaziar_carrinho);

                header("Location: " . FULL_ROUTER_URL . "?pg=pedidos/avaliar&pedido_id=" . $pedido_id . "&message_type=success&message=Compra finalizada com sucesso! Obrigado por comprar em nosso site. Agora, avalie sua experiência!");
                exit();

            } else {
                $mensagem = "Erro ao criar o pedido: " . mysqli_error($conexao);
                $mensagem_type = "error";
            }
        }
    }
} else {
    // Se a requisição não for POST, garante que o usuário esteja logado ou redireciona.
    if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
        header("Location: " . FULL_ROUTER_URL . "?pg=users/login&message=Por favor, faça login para finalizar a compra.");
        exit();
    }
}
// Carregar itens do carrinho para exibição no checkout (se não for POST)
$itens_carrinho_display = [];
$total_carrinho_display = 0;

$sql_carrinho_display = "SELECT c.quantidade, p.id as produto_id, p.nome, p.preco, p.desconto FROM carrinho c JOIN produtos p ON c.produto_id = p.id WHERE c.user_id = ?";
$stmt_carrinho_display = mysqli_prepare($conexao, $sql_carrinho_display);
mysqli_stmt_bind_param($stmt_carrinho_display, "i", $user_id);
mysqli_stmt_execute($stmt_carrinho_display);
$resultado_carrinho_display = mysqli_stmt_get_result($stmt_carrinho_display);

while ($row = mysqli_fetch_assoc($resultado_carrinho_display)) {
    $preco_unitario_com_desconto = $row['preco'] * (1 - $row['desconto']);
    $subtotal_item = $preco_unitario_com_desconto * $row['quantidade'];
    $total_carrinho_display += $subtotal_item;
    $itens_carrinho_display[] = [
        'nome' => htmlspecialchars($row['nome']),
        'preco_final_unitario' => number_format($preco_unitario_com_desconto, 2, ',', '.'),
        'quantidade' => htmlspecialchars($row['quantidade']),
        'subtotal' => number_format($subtotal_item, 2, ',', '.')
    ];
}
mysqli_stmt_close($stmt_carrinho_display);
mysqli_close($conexao);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - StarkWear</title>
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
        .checkout-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
        }
        .checkout-section {
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--coral-mar-cinzento);
            padding-bottom: 1.5rem;
        }
        .checkout-section:last-of-type { border-bottom: none; padding-bottom: 0; }
        h2 {
            color: var(--azul-marinho);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        h3 {
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
        .order-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed rgba(0,0,0,0.1);
            color: rgba(0,0,0,0.82);
        }
        .order-summary-item:last-child { border-bottom: none; }
        .order-total {
            font-size: 1.6rem;
            font-weight: bold;
            color: var(--azul-do-mar);
            text-align: right;
            margin-top: 1.5rem;
        }
        .payment-options label {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            cursor: pointer;
            color: var(--fundo-mar);
        }
        .payment-options input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        .payment-info {
            margin-top: 1rem;
            background: rgba(0,0,0,0.03);
            padding: 1rem;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(0,0,0,0.08);
            font-size: 0.9em;
            color: var(--muted);
        }
        .checkout-button {
            margin-top: 2rem;
            width: 100%;
            padding: 0.8rem 1.5rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: var(--white);
            box-shadow: var(--shadow-1);
            transition: all var(--transition);
            font-size: 1.2rem;
        }
        .checkout-button:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: var(--shadow-2);
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../topo.php"; ?>

    <div class="container">
        <h2>Finalizar Compra</h2>

        <?php if (!empty($mensagem)): ?>
            <div class="message-box <?= $mensagem_type ?>">
                <?= htmlspecialchars($mensagem) ?><br>
                <?php if ($mensagem_type == "error"): // Se houver um erro, oferece um botão para voltar ao carrinho ?>
                    <p class="text-center"><a href="<?= ROUTER_URL ?>?pg=carrinho/index" class="checkout-button" style="width: auto;">Voltar ao Carrinho</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($itens_carrinho_display)): ?>
            <div class="checkout-container">
                <h3 class="text-center">Seu carrinho está vazio. Adicione produtos antes de finalizar a compra.</h3>
                <p class="text-center"><a href="<?= ROUTER_URL ?>?pg=produtos/index" class="checkout-button" style="width: auto;">Ver Produtos</a></p>
            </div>
        <?php else: ?>
            <div class="checkout-container">
                <form action="<?= ROUTER_URL ?>?pg=pedidos/checkout" method="post">
                    <div class="checkout-section">
                        <h3>Resumo do Pedido</h3>
                        <?php foreach ($itens_carrinho_display as $item): ?>
                            <div class="order-summary-item">
                                <span><?= $item['nome'] ?> (x<?= $item['quantidade'] ?>)</span>
                                <span>R$ <?= $item['subtotal'] ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="order-total">Total: R$ <?= number_format($total_carrinho_display, 2, ',', '.') ?></div>
                    </div>

                    <div class="checkout-section">
                        <h3>Escolha a Forma de Pagamento</h3>
                        <div class="payment-options">
                            <label>
                                <input type="radio" name="metodo_pagamento" value="boleto_bancario" required>
                                Boleto Bancário
                            </label>
                            <label>
                                <input type="radio" name="metodo_pagamento" value="cartao_credito" required>
                                Cartão de Crédito (até 10x sem juros)
                            </label>
                            <div class="payment-info">
                                <p>Aceitamos as principais bandeiras brasileiras (simulado).</p>
                            </div>
                            <label>
                                <input type="radio" name="metodo_pagamento" value="debito" required>
                                Cartão de Débito
                            </label>
                            <label>
                                <input type="radio" name="metodo_pagamento" value="pix" required>
                                PIX
                            </label>
                            <div class="payment-info">
                                <p>Chave PIX: starkwear@pix.com.br (simulado).</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="checkout-button">Finalizar Compra</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once __DIR__ . "/../rodape.php"; ?>
</body>
</html>
