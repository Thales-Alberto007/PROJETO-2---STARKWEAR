<?php
require_once "conexao.php";

$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// 1. Verificar se o e-mail já existe
$check = "SELECT id FROM usuarios WHERE email = '$email'";
$result = mysqli_query($con, $check);

if (mysqli_num_rows($result) > 0) {
    // E-mail repetido → mensagem amigável + botão voltar
    echo "<script>
            alert('E-mail já cadastrado! Tente outro ou faça login.');
            window.location.href = 'login.php';
          </script>";
    exit;
}

// 2. Se não existir, cadastrar normalmente
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if (mysqli_query($con, $sql)) {
    echo "<script>
            alert('Usuário cadastrado com sucesso!');
            window.location.href = 'login.php';
          </script>";
} else {
    echo "<script>
            alert('Erro ao cadastrar!');
            history.back();
          </script>";
}
?>
