<?php
// FORMULÁRIO DE NOVO USUÁRIO
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Usuário</title>

<style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", Arial, sans-serif;
}

/* ===== FUNDO ===== */
body {
    background: #e0e0e0; /* fundo neutro */
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333;
}

/* ===== CONTAINER ===== */
.container {
    background: #ffffff;
    padding: 45px 40px; /* um pouco mais espaçado */
    border-radius: 16px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    width: 420px;
    display: flex;
    flex-direction: column;
    gap: 22px; /* mais espaçamento entre elementos */
    animation: fadeIn 0.5s ease;
}

/* ===== ANIMAÇÃO ===== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== TÍTULO ===== */
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #222;
    font-size: 28px; /* fonte maior */
    font-weight: 600;
}

/* ===== INPUT ===== */
input[type="text"],
input[type="email"],
input[type="password"] {
    padding: 16px; /* maior padding para melhor visual */
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 17px; /* fonte maior */
    outline: none;
    transition: 0.3s;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px #007bff44;
}

/* ===== BOTÃO ===== */
button.btn {
    margin-top: 10px;
    padding: 16px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 17px; /* fonte maior */
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
}

button.btn:hover {
    background: #0056b3;
}

/* ===== LINK VOLTAR ===== */
a {
    margin-top: 12px;
    text-align: center;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px; /* fonte maior */
    transition: 0.25s;
}

a:hover {
    text-decoration: underline;
}

/* ===== FORM ===== */
form {
    display: flex;
    flex-direction: column;
    gap: 18px; /* espaçamento maior */
}
</style>

</head>
<body>

<div class="container">
    <h2>Novo Usuário</h2>

    <form action="usuarios_inserir.php" method="POST">
        <input type="text" name="nome" placeholder="Nome completo" required>

        <input type="email" name="email" placeholder="E-mail" required>

        <input type="password" name="senha" placeholder="Senha" required>

        <button class="btn" type="submit">Cadastrar</button>
    </form>

    <a href="usuarios_listar.php">Voltar</a>
</div>

</body>
</html>
