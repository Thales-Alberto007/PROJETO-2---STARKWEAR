<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/admin/config.inc.php"; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: " . FULL_ROUTER_URL . "?pg=users/login&message=Por favor, faça login para adicionar produtos ao carrinho.");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST["produto_id"] ?? '';
    $quantidade = $_POST["quantidade"] ?? 1;

    // Garante que a quantidade é um número inteiro positivo
    $quantidade = max(1, (int)$quantidade);

    if (empty($produto_id)) {
        header("Location: " . FULL_ROUTER_URL . "?pg=produtos/index&message_type=error&message=ID do produto inválido.");
        exit();
    }

    // Verifica se o produto já está no carrinho do usuário
    $stmt_check = mysqli_prepare($conexao, "SELECT id, quantidade FROM carrinho WHERE user_id = ? AND produto_id = ?");
    mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $produto_id);
    mysqli_stmt_execute($stmt_check);
    $resultado_check = mysqli_stmt_get_result($stmt_check);

    if ($item_carrinho = mysqli_fetch_assoc($resultado_check)) {
        // Produto já existe no carrinho, atualiza a quantidade
        $nova_quantidade = $item_carrinho['quantidade'] + $quantidade;
        $stmt_update = mysqli_prepare($conexao, "UPDATE carrinho SET quantidade = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_update, "ii", $nova_quantidade, $item_carrinho['id']);
        if (mysqli_stmt_execute($stmt_update)) {
            header("Location: " . FULL_ROUTER_URL . "?pg=carrinho/index&message_type=success&message=Quantidade do produto atualizada no carrinho!");
        } else {
            header("Location: " . FULL_ROUTER_URL . "?pg=carrinho/index&message_type=error&message=Erro ao atualizar quantidade no carrinho.");
        }
        mysqli_stmt_close($stmt_update);
    } else {
        // Produto não está no carrinho, insere um novo item
        $stmt_insert = mysqli_prepare($conexao, "INSERT INTO carrinho (user_id, produto_id, quantidade) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "iii", $user_id, $produto_id, $quantidade);
        if (mysqli_stmt_execute($stmt_insert)) {
            header("Location: " . FULL_ROUTER_URL . "?pg=carrinho/index&message_type=success&message=Produto adicionado ao carrinho com sucesso!");
        } else {
            header("Location: " . FULL_ROUTER_URL . "?pg=carrinho/index&message_type=error&message=Erro ao adicionar produto ao carrinho.");
        }
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);
} else {
    header("Location: " . FULL_ROUTER_URL . "?pg=produtos/index&message_type=error&message=Método de requisição inválido.");
}
mysqli_close($conexao);
exit();
?>
