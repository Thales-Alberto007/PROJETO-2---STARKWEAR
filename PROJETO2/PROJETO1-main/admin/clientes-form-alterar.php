<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "config.inc.php";

$id = $_GET['id'] ?? '';
$cliente = [
    'nome' => '',
    'email' => '',
    'cidade' => '',
    'estado' => ''
];

if (!empty($id)) {
    $stmt = mysqli_prepare($conexao, "SELECT id, nome, email, cidade, estado FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        $cliente = mysqli_fetch_assoc($resultado);
    } else {
        echo "<p style=\"color:red; text-align:center;\">Cliente não encontrado!</p>";
        exit();
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Cadastro do Cliente</title>
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
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: calc(100% - 22px); /* Ajuste para padding */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        a.button-back {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        a.button-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <?php include_once "../topo.php"; // Inclui o topo para o estilo ?>

    <div class="container-admin">
        <h2>Alterar Cadastro do Cliente</h2>

        <form action="?pg=clientes-alterar" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id'] ?? '') ?>">

            <div class="form-group">
                <label for="cliente">Nome:</label>
                <input type="text" id="cliente" name="cliente" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>">
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?= htmlspecialchars($cliente['estado']) ?>">
            </div>

            <button type="submit">Salvar Alterações</button>
            <a href="?pg=clientes-admin" class="button-back">Cancelar</a>
        </form>
    </div>

    <?php include_once "../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
