<?php

    require_once "admin/config.inc.php";
    $sql = "SELECT * FROM clientes";
    $resultado = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($resultado) > 0) {

    echo "<h2>Nossos Clientes</h2>";
?>
<div>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php
            while($dados = mysqli_fetch_array($resultado)) {
        ?>
        <tr>
            <td><?=$dados['cliente']?></td>
            <td><?=$dados['cidade']?></td>
            <td><?=$dados['estado']?></td>
        </tr>
        <?php
            }
        ?>

        </tbody>
    </table>
</div>
<?php
    }else{
        echo "<h2>Nenhum Cliente cadastrado</h2>";
    }
?>