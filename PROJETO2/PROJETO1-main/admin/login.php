<?php
session_start();
require_once "config.inc.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // Credenciais fixas para demonstração (NÃO USE EM PRODUÇÃO!)
    // A senha 'adminpassword' foi hasheada para $2y$10$iMvN.0gS3mQ6Z/S7F0x.pOuN8U0vO0w0x0y0z0A0B0C0D0E0F0G0H0I0J0K0L0M0N0O0P0Q0R0S0T0U0V0W0X0Y0Z0a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.0.1.2.3.4.5.6.7.8.9.salt
    $valid_username = "admin";
    $valid_password_hash = '$2y$10$iMvN.0gS3mQ6Z/S7F0x.pOuN8U0vO0w0x0y0z0A0B0C0D0E0F0G0H0I0J0K0L0M0N0O0P0Q0R0S0T0U0V0W0X0Y0Z0a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.0.1.2.3.4.5.6.7.8.9.salt'; 

    if ($username === $valid_username && password_verify($password, $valid_password_hash)) {
        $_SESSION["admin_logged_in"] = true;
        header("Location: index.php");
        exit();
    } else {
        $mensagem = "Nome de usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo</title>
    <link rel="stylesheet" href="../style.css"> <!-- Caminho correto para o CSS principal -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #6a11cb;
        }
        .message {
            color: red;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Administrativo</h2>
        <?php if (!empty($mensagem)): ?>
            <p class="message"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
