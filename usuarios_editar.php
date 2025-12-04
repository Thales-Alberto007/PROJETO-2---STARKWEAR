<?php
require_once "conexao.php";

$id = $_GET['id'];
$sql = "SELECT * FROM usuarios WHERE id=$id";
$res = mysqli_query($con, $sql);
$usuario = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style_editar.css">
</head>
<div class="form-container">
    <h2>Editar Usuário</h2>
    <form action="usuarios_update.php" method="post" class="form">
        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

        <label>Nome</label>
        <input type="text" name="nome" value="<?php echo $usuario['nome']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>

        <button type="submit">Salvar</button>
    </form>
</div>
