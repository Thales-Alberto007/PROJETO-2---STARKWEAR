<?php

require_once "../admin/config.inc.php";

$sql = "SELECT * FROM produtos";
$resultado = mysqli_query($conexao, $sql);

// --- ESTILO PRAIANO ---
echo "
<style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background: linear-gradient(to bottom, #69D8C4, #F8E9C8);
        padding: 40px;
        text-align: center;
        color: #034D66;
    }

    h2 {
        font-size: 34px;
        color: #055A7A;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #2EC4B6;
        padding: 12px 25px;
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-size: 18px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #ff7b6b;
        transform: translateY(-2px);
    }

    table {
        width: 85%;
        margin: 25px auto;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        animation: fadeIn 0.7s ease-in-out;
    }

    th, td {
        padding: 15px;
        font-size: 18px;
        color: #034D66;
        border-bottom: 2px solid #E0E0E0;
    }

    th {
        background-color: #2EC4B6;
        color: white;
        font-size: 20px;
    }

    tr:hover {
        background-color: #FCE8D6;
        transition: 0.3s;
    }

    a {
        color: #05668D;
        font-weight: bold;
        text-decoration: none;
    }

    a:hover {
        color: #ff7b6b;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>
";

echo "<h2>🌴 Lista de Produtos 🌴</h2>";
echo "<a href='form_cadastrar.php' class='btn-primary'>Cadastrar Produto</a><br><br>";

if (mysqli_num_rows($resultado) > 0) {
?>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
            while($dados = mysqli_fetch_array($resultado)) {
        ?>
            <tr>
                <td><?=$dados['nome']?></td>
                <td><?=$dados['preco']?></td>
                <td><?=$dados['descricao']?></td>
                <td>
                    <a href="form_editar.php?id=<?=$dados['id']?>">Editar</a> |
                    <a href="excluir.php?id=<?=$dados['id']?>" onclick="return confirm('Confirma exclusão?');">Excluir</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php
} else {
    echo "<h3 style='color:#B83A24;'>Nenhum produto cadastrado.</h3>";
}
?>
