<?php

require_once 'config.inc.php';

$id = $_GET['id'];
$sql = "SELECT * FROM clientes WHERE id = '$id'";
$resultado = mysqli_query($conexao, $sql);

if(mysqli_num_rows($resultado) > 0){
    while($dados = mysqli_fetch_array($resultado)){
        $nome = $dados['cliente'];
        $cidade = $dados['cidade'];
        $estado = $dados['estado'];
        $id = $dados['id'];
    }
?>

<h2>Editar Cliente</h2>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: linear-gradient(to bottom, #a1dffb, #fef6d9);
        margin: 0;
        padding: 40px;
        text-align: center;
    }

    form {
        background: #ffffffcc;
        backdrop-filter: blur(3px);
        max-width: 440px;
        margin: 0 auto;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 0 12px rgba(0,0,0,0.15);
        text-align: left;
    }

    h2 {
        text-align: center;
        color: #085c85;
        margin-bottom: 25px;
        font-size: 26px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
        color: #0b4f6c;
    }

    input[type=text] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #63b6e6;
        border-radius: 6px;
        font-size: 15px;
        background: #ffffffdd;
    }

    input[type=submit] {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
        background: #0b79bf;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    }

    input[type=submit]:hover {
        background: #095f92;
    }
</style>

<form action="?pg=clientes-alterar" method="post">
    <input type="hidden" name="id" value="<?=$id?>">

    <label>Nome:</label>
    <input type="text" name="cliente" value="<?=$nome?>">

    <label>Cidade:</label>
    <input type="text" name="cidade" value="<?=$cidade?>">

    <label>Estado:</label>
    <input type="text" name="estado" value="<?=$estado?>">

    <input type="submit" value="Salvar Alterações">
</form>

<?php
}else{
    echo "<h2 style='color:#0b4f6c;'>Nenhum cliente encontrado!</h2>";
}
?>
