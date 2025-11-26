<?php
include "../../conexao.php";

$estampas = $con->query("
    SELECT e.*, c.nome_colecao 
    FROM estampas e
    LEFT JOIN colecoes c ON c.id_colecao = e.id_colecao
    ORDER BY id_estampa DESC
");
?>

<h2>Estampas</h2>
<a href="criar.php">+ Nova Estampa</a>

<ul>
<?php while ($e = $estampas->fetch_assoc()) : ?>
    <li>
        <b><?= $e['nome_estampa'] ?></b> | Coleção: <?= $e['nome_colecao'] ?>
        
        <?php if ($e['imagem_estampa']) : ?>
            <br><img src="../../<?= $e['imagem_estampa'] ?>" width="80">
        <?php endif; ?>

        <br>
        [<a href="editar.php?id=<?= $e['id_estampa'] ?>">Editar</a>]
        [<a href="excluir.php?id=<?= $e['id_estampa'] ?>">Excluir</a>]
    </li>
<?php endwhile; ?>
</ul>
