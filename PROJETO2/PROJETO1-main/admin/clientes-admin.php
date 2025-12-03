<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once "config.inc.php";

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="../style.css"> <!-- Caminho correto para o CSS principal -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container-admin {
            max-width: 960px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .button-group {
            text-align: center;
            margin-bottom: 20px;
        }
        .button-group a {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button-group a:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .actions a.delete {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <?php include_once "../topo.php"; // Inclui o topo para o estilo ?>

    <div class="container-admin">
        <h2>Lista de Clientes</h2>

        <div class="button-group">
            <a href="?pg=clientes-form">Cadastrar Cliente</a>
            <a href="index.php">Voltar ao Painel</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nome, email, cidade, estado FROM users"; // Tabela agora é 'users'
                $resultado = mysqli_query($conexao, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while($dados = mysqli_fetch_array($resultado)) {
                ?>
                        <tr>
                            <td><?= htmlspecialchars($dados['id']) ?></td>
                            <td><?= htmlspecialchars($dados['nome']) ?></td>
                            <td><?= htmlspecialchars($dados['email']) ?></td>
                            <td><?= htmlspecialchars($dados['cidade']) ?></td>
                            <td><?= htmlspecialchars($dados['estado']) ?></td>
                            <td class="actions">
                                <a href="?pg=clientes-form-alterar&id=<?= htmlspecialchars($dados['id']) ?>">Editar</a>
                                <a href="?pg=clientes-excluir&id=<?= htmlspecialchars($dados['id']) ?>" onclick="return confirm('Confirma exclusão?');">Excluir</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan=\"6\" style=\"text-align:center;\">Nenhum cliente cadastrado!</td></tr>";
                }
                mysqli_close($conexao);
                ?>
            </tbody>
        </table>
    </div>

    <?php include_once "../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
