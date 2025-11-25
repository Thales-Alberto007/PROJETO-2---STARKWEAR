<?php

    require_once "config.inc.php";
    $id = $_GET["id"];
    $sql = "DELETE FROM clientes WHERE id = '$id'";

    $resultado = mysqli_query($conexao, $sql);

    echo "<div style='
            font-family: Arial, Helvetica, sans-serif;
            padding: 25px;
            margin: 60px auto;
            width: 380px;
            text-align: center;
            background: #ffffffd9;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.25);
            backdrop-filter: blur(4px);
            background-image: linear-gradient(to bottom, #87d8ff, #ffe9b3);
        '>";

    echo "<div style='
            background: #ffffffcc;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            display: inline-block;
        '>";

    if($resultado){
        echo "<h2 style='color:#003b5c; margin:0;'>Registro excluído com sucesso!</h2>";
    }else{
        echo "<h2 style='color:#003b5c; margin:0;'>Erro ao excluir registro!</h2>";
    }

    echo "</div>";

    // Botão de voltar
    echo "
        <br><br>
        <a href='?pg=clientes-admin' 
        style='
            display: inline-block;
            padding: 10px 18px;
            background: #ffd37c;
            color: #5a3d0c;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.30);
            font-size: 16px;
            font-weight: bold;
            transition: 0.2s;
        '
        onmouseover=\"this.style.background='#ffca5c'\"
        onmouseout=\"this.style.background='#ffd37c'\"
        >Voltar</a>
    ";

    echo "</div>";

    mysqli_close($conexao);
?>
