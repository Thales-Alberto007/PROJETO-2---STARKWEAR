<?php require_once "config.inc.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(to bottom, #87d8ff, #ffe9b3);
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            color: #003b5c;
            background: #ffffffcc;
            display: inline-block;
            padding: 10px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 5px #00000030;
            margin: 10px auto 25px auto;
        }

        .top-buttons {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 18px;
            background: #5cc6ff;
            color: #003b5c;
            text-decoration: none;
            border-radius: 10px;
            margin-right: 10px;
            box-shadow: 0 2px 6px #00000030;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn:hover {
            background: #4db9f0;
        }

        .btn-back {
            background: #ffd37c;
            color: #5a3d0c;
        }

        .btn-back:hover {
            background: #ffca5c;
        }

        .cliente-box {
            background: #ffffffd9;
            max-width: 700px;
            margin: 15px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px #00000030;
            backdrop-filter: blur(4px);
        }

        .cliente-box p {
            margin: 6px 0;
            font-size: 16px;
            color: #003b5c;
        }

        .actions a {
            text-decoration: none;
            font-weight: bold;
            margin-right: 12px;
            font-size: 15px;
        }

        .edit {
            color: #0077cc;
        }

        .edit:hover {
            color: #005a99;
        }

        .delete {
            color: #d10000;
        }

        .delete:hover {
            color: #a30000;
        }

        hr {
            border: 0;
            height: 1px;
            background: #c9c9c9;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<div class="top-buttons">
    <a href="?pg=clientes-form" class="btn">Cadastrar Cliente</a>
    <a href="?pg=clientes-admin" class="btn btn-back">Voltar</a>
</div>

<h2>Lista de Clientes</h2>

<?php

$sql = "SELECT * FROM clientes";
$resultado = mysqli_query($conexao, $sql);

if (mysqli_num_rows($resultado) > 0) {

    while($dados = mysqli_fetch_array($resultado)) {

        echo "<div class='cliente-box'>";

        echo "<p><strong>ID:</strong> {$dados['id']}</p>";
        echo "<p><strong>Nome:</strong> {$dados['cliente']}</p>";
        echo "<p><strong>Cidade:</strong> {$dados['cidade']}</p>";
        echo "<p><strong>Estado:</strong> {$dados['estado']}</p>";

        echo "<div class='actions'>
                <a class='edit' href='?pg=clientes-form-alterar&id={$dados['id']}'>Editar</a>
                <a class='delete' href='?pg=clientes-excluir&id={$dados['id']}' onclick=\"return confirm('Confirma exclusão?');\">Excluir</a>
              </div>";

        echo "</div>";
    }

} else {
    echo "<p style='text-align:center; font-size: 18px; background:#ffffffcc; padding:10px 20px; border-radius:10px; display:inline-block; margin:auto; box-shadow:0 2px 5px #00000030;">Nenhum cliente cadastrado!</p>";
}

?>

</body>
</html>
