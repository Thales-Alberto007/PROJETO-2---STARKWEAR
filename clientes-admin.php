<?php require_once "config.inc.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #222;
        }

        .top-buttons {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 15px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        .btn:hover {
            background: #333;
        }

        .btn-back {
            background: #444;
        }

        .cliente-box {
            background: white;
            max-width: 700px;
            margin: 15px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        .cliente-box p {
            margin: 5px 0;
            font-size: 16px;
        }

        .actions a {
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
        }

        .edit {
            color: #007bff;
        }

        .delete {
            color: red;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ddd;
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
    echo "<p style='text-align:center; font-size: 18px;'>Nenhum cliente cadastrado!</p>";
}

?>

</body>
</html>
