<?php require_once "config.inc.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 40px;
        }

        .box {
            max-width: 450px;
            margin: 0 auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.15);
            text-align: center;
        }

        h2, h3 {
            color: #222;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }

        a:hover {
            background: #333;
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
