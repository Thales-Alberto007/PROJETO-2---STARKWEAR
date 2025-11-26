<?php

require_once "../admin/config.inc.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $preco = $_POST["preco"];
    $descricao = $_POST["descricao"];

    $sql = "UPDATE produtos SET 
            nome = '$nome',
            preco = '$preco',
            descricao = '$descricao'
            WHERE id = '$id'";

    // Estilo praiano
    echo "
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(to bottom, #6DD5C4, #F7E7C1);
            text-align: center;
            padding: 50px;
            color: #034D66;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            padding: 25px;
            border-radius: 20px;
            width: 420px;
            margin: auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            backdrop-filter: blur(6px);
            animation: fadeIn 0.7s ease-in-out;
        }

        h3 {
            font-size: 26px;
            color: #05668D;
            margin-bottom: 15px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #2EC4B6;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            transition: 0.3s;
            font-weight: bold;
        }

        a:hover {
            background-color: #ff7b6b;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    ";

    if(mysqli_query($conexao, $sql)){
        echo "<div class='card'>
                <h3>🌴 Produto alterado com sucesso! 🌴</h3>
                <a href='index.php'>Voltar</a>
              </div>";
    } else {
        echo "<div class='card'>
                <h3 style='color:#B83A24;'>⚠ Erro ao alterar! ⚠</h3>
                <a href='index.php'>Voltar</a>
              </div>";
    }

} else {
    echo "Acesso negado!";
}
?>
