<?php
include "../../conexao.php";

$id = intval($_GET["id"]);

$stmt = $con->prepare("SELECT * FROM estampas WHERE id_estampa=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$estampa = $stmt->get_result()->fetch_assoc();

$colecoes = $con->query("SELECT * FROM colecoes");
?>

<form action="salvar.php" method="post" enctype="multipart/form-data">
    <h2>Editar Estampa</h2>

    <input type="hidden" name="id" value="<?= $estampa['id_estampa'] ?>">

    <label>Nome:</label>
    <input name="nome_estampa" value="<?= $estampa['nome_estampa'] ?>">

    <label>Descrição:</label>
    <textarea name="descricao"><?= $estampa['descricao'] ?></textarea>

    <label>Coleção:</label>
    <select name="id_colecao">
        <?php while ($c = $colecoes->fetch_assoc()) : ?>
            <option value="<?= $c['id_colecao'] ?>" <?= $c['id_colecao'] == $estampa['id_colecao'] ? "selected" : "" ?>>
                <?= $c['nome_colecao'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Imagem (opcional):</label>
    <input type="file" name="imagem_estampa">

    <?php if ($estampa['imagem_estampa']) : ?>
        <img src="../../<?= $estampa['imagem_estampa'] ?>" width="80">
    <?php endif; ?>

    <button>Salvar</button>
</form>
