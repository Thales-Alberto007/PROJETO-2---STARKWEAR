<?php require_once "config.inc.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(to bottom, #87d8ff, #ffe9b3);
            min-height: 100vh;
        }

        .box {
            max-width: 450px;
            margin: 0 auto;
            padding: 25px;
            background: #ffffffd9;
            border-radius: 12px;
            box-shadow: 0 3px 10px #00000035;
            text-align: center;
            backdrop-filter: blur(4px);
        }

        h2, h3 {
            color: #003b5c;
            background: #ffffffcc;
            padding: 10px 15px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 2px 5px #00000030;
        }

        a {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 18px;
            background: #ffd37c;
            color: #5a3d0c;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 2px 6px #00000030;
            transition: 0.2s;
            font-weight: bold;
        }

        a:hover {
            background: #ffca5c;
        }
    </style>
</head>

<body>

<div class="box">

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST["cliente"];
        $cidade = $_POST["cidade"];
        $estado = $_POST["estado"];

        $sql = "INSERT INTO clientes (cliente, cidade, estado)
                VALUES ('$nome', '$cidade', '$estado')";

        if(mysqli_query($conexao, $sql)){
            echo "<h3>Cliente cadastrado com sucesso!</h3>";
        } else {
            echo "<h3>Erro ao cadastrar cliente!</h3>";
        }

        echo "<a href='?pg=clientes-admin'>Voltar</a>";

    } else {
        echo "<h2>Acesso negado!</h2>";
        echo "<a href='?pg=clientes-admin'>Voltar</a>";
    }

    mysqli_close($conexao);
?>

</div>

</body>
</html>
