<?php
include "../../conexao.php";
$id = intval($_GET['id']);
$stmt = $con->prepare("SELECT * FROM colecoes WHERE id_colecao=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$colecao = $stmt->get_result()->fetch_assoc();
?>

<form action="salvar.php" method="post">
    <h2>Editar Coleção</h2>

    <input type="hidden" name="id" value="<?= $colecao['id_colecao'] ?>">

    <label>Nome:</label>
    <input name="nome_colecao" value="<?= $colecao['nome_colecao'] ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao"><?= $colecao['descricao'] ?></textarea>

    <label>Desconto (%):</label>
    <input type="number" step="0.01" name="desconto" value="<?= $colecao['desconto'] ?>">

    <button type="submit">Salvar</button>
</form>
