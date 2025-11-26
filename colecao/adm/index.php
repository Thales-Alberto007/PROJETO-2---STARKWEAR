<?php
include "../../conexao.php";
$colecoes = $con->query("SELECT * FROM colecoes ORDER BY id_colecao DESC");
?>

<h2>Coleções</h2>
<a href="criar.php">+ Nova Coleção</a>

<ul>
<?php while ($c = $colecoes->fetch_assoc()) : ?>
    <li>
        <b><?= $c['nome_colecao'] ?></b> -
        <?= $c['descricao'] ?> -
        Desconto: <?= $c['desconto'] ?>%

        [<a href="editar.php?id=<?= $c['id_colecao'] ?>">Editar</a>]
        [<a href="excluir.php?id=<?= $c['id_colecao'] ?>">Excluir</a>]
    </li>
<?php endwhile; ?>
</ul>
