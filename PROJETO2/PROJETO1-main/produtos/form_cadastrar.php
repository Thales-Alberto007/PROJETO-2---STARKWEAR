<?php
// Verifica se o administrador está logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: ../login.php"); // Caminho ajustado para o login do admin
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Novo Produto</title>
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
        input[type="number"],
        textarea,
        select {
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
        <h2>Cadastrar Novo Produto</h2>

        <form action="?pg=produtos/cadastrar" method="post">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="colecao">Coleção:</label>
                <select id="colecao" name="colecao" required>
                    <option value="">Selecione uma coleção</option>
                    <option value="Sunset Wave">Sunset Wave</option>
                    <option value="Maré Alta">Maré Alta</option>
                    <option value="Blue Horizon">Blue Horizon</option>
                    <option value="Areia Quente">Areia Quente</option>
                    <option value="Beach Soul">Beach Soul</option>
                </select>
            </div>

            <div class="form-group">
                <label for="imagem">Caminho da Imagem:</label>
                <input type="text" id="imagem" name="imagem" placeholder="Ex: assets/img/nome_d-imagem.jpg">
            </div>

            <div class="form-group">
                <label for="desconto">Desconto (0 a 1, ex: 0.35 para 35%):</label>
                <input type="number" id="desconto" name="desconto" step="0.01" min="0" max="1" value="0.00">
            </div>

            <button type="submit">Cadastrar Produto</button>
            <a href="?pg=produtos/index" class="button-back">Cancelar</a>
        </form>
    </div>

    <?php include_once "../rodape.php"; // Inclui o rodapé ?>
</body>
</html>
