<form action="salvar.php" method="post">
    <h2>Criar Coleção</h2>

    <label>Nome:</label>
    <input name="nome_colecao" required>

    <label>Descrição:</label>
    <textarea name="descricao"></textarea>

    <label>Desconto (%):</label>
    <input type="number" step="0.01" name="desconto">

    <button type="submit">Salvar</button>
</form>
