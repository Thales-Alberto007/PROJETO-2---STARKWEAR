<?php
// Verifica se o administrador está logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: ../login.php"); // Caminho ajustado para o login do admin
    exit();
}

require_once "../admin/config.inc.php";

$mensagem = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST["id"] ?? '';
    $nome = $_POST["nome"] ?? '';
    $preco = $_POST["preco"] ?? '';
    $descricao = $_POST["descricao"] ?? '';
    $colecao = $_POST["colecao"] ?? '';
    $imagem = $_POST["imagem"] ?? ''; // Por enquanto, caminho da imagem é manual
    $desconto = $_POST["desconto"] ?? 0.00;

    // Validação básica
    if (empty($id) || empty($nome) || empty($preco) || empty($colecao)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios (ID, Nome, Preço, Coleção).";
    } elseif (!is_numeric($preco) || $preco <= 0) {
        $mensagem = "Preço inválido.";
    } elseif (!is_numeric($desconto) || $desconto < 0 || $desconto > 1) {
        $mensagem = "Desconto inválido. Use um valor entre 0 e 1 (ex: 0.35 para 35%).";
    } else {
        // Prepared Statement para atualizar o produto
        $stmt = mysqli_prepare($conexao, "UPDATE produtos SET nome = ?, preco = ?, descricao = ?, colecao = ?, imagem = ?, desconto = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sdssdsi", $nome, $preco, $descricao, $colecao, $imagem, $desconto, $id); // 'sdssdsi' para string, decimal, string, string, decimal, string, integer

        if(mysqli_stmt_execute($stmt)){
            $mensagem = "Produto alterado com sucesso!";
        }else{
            $mensagem = "Erro ao alterar produto: " . mysqli_error($conexao);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexao);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status Alteração de Produto</title>
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
        <h2>Status da Alteração do Produto</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="message <?= (strpos($mensagem, 'sucesso') !== false) ? 'success' : 'error' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>
        <a href="?pg=produtos/index" class="button">Voltar para Produtos</a>
    </div>

    <?php include_once "../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
