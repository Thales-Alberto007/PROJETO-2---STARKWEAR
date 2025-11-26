<?php include "../../conexao.php"; ?>

<?php
$colecoes = $con->query("SELECT * FROM colecoes");
?>

<form action="salvar.php" method="post" enctype="multipart/form-data">
    <h2>Criar Estampa</h2>

    <label>Nome:</label>
    <input name="nome_estampa" required>

    <label>Descrição:</label>
    <textarea name="descricao"></textarea>

    <label>Coleção:</label>
    <select name="id_colecao">
        <option value="">Selecione</option>
        <?php while ($c = $colecoes->fetch_assoc()) : ?>
            <option value="<?= $c['id_colecao'] ?>"><?= $c['nome_colecao'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Imagem:</label>
    <input type="file" name="imagem_estampa">

    <button>Salvar</button>
</form>
