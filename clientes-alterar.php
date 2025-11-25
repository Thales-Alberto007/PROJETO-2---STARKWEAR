<?php

require_once "config.inc.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cliente = $_POST["cliente"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];
    $id = $_POST["id"];

    $sql = "UPDATE clientes SET 
            cliente = '$cliente',
            cidade = '$cidade',
            estado = '$estado'
            WHERE id = '$id'";

    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Alterar Cliente</title>
        <style>
            body {
                font-family: Arial;
                padding: 20px;
                background: linear-gradient(to bottom, #87d8ff, #ffe9b3);
                height: 100vh;
                margin: 0;
            }
            h3, h2 {
                background: #ffffffcc;
                padding: 10px 15px;
                border-radius: 10px;
                display: inline-block;
                box-shadow: 0 2px 5px #00000030;
            }
            .erro {
                color: #d10000;
            }
            a {
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background: #ffd37c;
                color: #333;
                text-decoration: none;
                border-radius: 10px;
                box-shadow: 0 2px 5px #00000030;
                transition: 0.2s;
            }
            a:hover {
                background: #ffca5c;
            }
        </style>
    </head>
    <body>

    <?php
    if(mysqli_query($conexao, $sql)){
        echo "<h3>Cliente alterado com sucesso!</h3>";
        echo "<br><a href='?pg=clientes-admin'>Voltar</a>";
    }else{
        echo "<h3 class='erro'>Erro ao alterar cadastro do cliente!</h3>";
        echo "<br><a href='?pg=clientes-admin'>Voltar</a>";
    }
    ?>

    </body>
    </html>

    <?php

}else{
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Acesso negado</title>
        <style>
            body {
                font-family: Arial;
                padding: 20px;
                background: linear-gradient(to bottom, #87d8ff, #ffe9b3);
                height: 100vh;
                margin: 0;
            }
            h2 {
                background: #ffffffcc;
                padding: 10px 15px;
                border-radius: 10px;
                display: inline-block;
                box-shadow: 0 2px 5px #00000030;
            }
            a {
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background: #ffd37c;
                color: #333;
                text-decoration: none;
                border-radius: 10px;
                box-shadow: 0 2px 5px #00000030;
                transition: 0.2s;
            }
            a:hover {
                background: #ffca5c;
            }
        </style>
    </head>
    <body>
        <h2>Acesso negado!</h2>
        <br>
        <a href='?pg=clientes-admin'>Voltar</a>
    </body>
    </html>
    <?php
}

mysqli_close($conexao);
?>
