<?php

    require_once "config.inc.php";
    $id = $_GET["id"];
    $sql = "DELETE FROM clientes WHERE id = '$id'";

    $resultado = mysqli_query($conexao, $sql);

    echo "<div style='
            font-family: Arial;
            padding: 20px;
            margin: 40px auto;
            width: 350px;
            text-align: center;
            background: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        '>";

    if($resultado){
        echo "<h2>Registro excluído com sucesso!</h2>";
    }else{
        echo "<h2>Erro ao excluir registro!</h2>";
    }

    // Botão de voltar
    echo "
        <br>
        <a href='?pg=clientes-admin' 
        style='
            display: inline-block;
            padding: 10px 15px;
            background: black;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        '>Voltar</a>
    ";

    echo "</div>";

    mysqli_close($conexao);
?>
