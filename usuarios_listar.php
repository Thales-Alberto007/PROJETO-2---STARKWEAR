<?php
session_start();
require_once "conexao.php";

$sql = "SELECT * FROM usuarios";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link rel="stylesheet" href="style_usuarios_listar.css">
</head>
<body>

<h2>Usuários</h2>
<a href="dashboard.php">Voltar</a>
<br><br>

<a href="usuarios_novo.php" class="btn">+ Novo Usuário</a>
<br><br>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Ações</th>
</tr>

<?php while ($u = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= $u['nome'] ?></td>
    <td><?= $u['email'] ?></td>
    <td>
        <a href="usuarios_editar.php?id=<?= $u['id'] ?>">Editar</a> |
        <a href="usuarios_excluir.php?id=<?= $u['id'] ?>"
           onclick="return confirm('Tem certeza?')">Excluir</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
