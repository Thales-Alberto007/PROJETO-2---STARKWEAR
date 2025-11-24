<?php require_once "config.inc.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
</head>

<body>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST["cliente"];
        $cidade = $_POST["cidade"];
        $estado = $_POST["estado"];

        $sql = "INSERT INTO clientes (cliente, cidade, estado)
                VALUES ('$nome', '$cidade', '$estado')";

        if(mysqli_query($conexao, $sql)){
            // REDIRECIONA PARA O MENU
            header("Location: menu.php");
            exit;
        } else {
            echo "<h3>Erro ao cadastrar cliente!</h3>";
            echo "<a href='?pg=clientes-admin'>Voltar</a>";
        }

    } else {
        echo "<h2>Acesso negado!</h2>";
        echo "<a href='?pg=clientes-admin'>Voltar</a>";
    }

    mysqli_close($conexao);
?>
</body>
</html>
