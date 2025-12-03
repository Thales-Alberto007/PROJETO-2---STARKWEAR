<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

// Área do conteúdo
$allowed_pages = [
    "clientes-admin",
    "clientes-form",
    "clientes-form-alterar",
    "clientes-alterar",
    "clientes-cadastro",
    "clientes-excluir",
    "produtos/index", // Caminho corrigido para produtos
    "produtos/form_cadastrar", // Caminho corrigido para produtos
    "produtos/cadastrar", // Caminho corrigido para produtos
    "produtos/form_editar", // Caminho corrigido para produtos
    "produtos/editar", // Caminho corrigido para produtos
    "produtos/excluir" // Caminho corrigido para produtos
];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../style.css"> <!-- Caminho correto para o CSS principal -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
            padding: 2rem 0;
        }
        .admin-panel {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: var(--radius);
            box-shadow: var(--shadow-2);
            text-align: center;
        }
        h1 {
            color: var(--azul-marinho);
            margin-bottom: 1.5rem;
        }
        h3 {
            color: var(--fundo-mar);
            margin-bottom: 1.5rem;
        }
        .admin-links a {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            margin: 0.5rem;
            background: linear-gradient(90deg, var(--azul-do-mar), var(--verde-mar));
            color: white;
            text-decoration: none;
            border-radius: 999px;
            font-weight: 600;
            box-shadow: var(--shadow-1);
            transition: all var(--transition);
        }
        .admin-links a:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: var(--shadow-2);
        }
        .admin-links .logout-btn {
            background: linear-gradient(90deg, var(--areia), var(--areia-1));
            color: var(--azul-marinho);
        }
        .admin-links .logout-btn:hover {
            background: linear-gradient(90deg, var(--areia-1), var(--areia-2));
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php include_once "../topo.php"; // Inclui o topo para o estilo ?>

    <div class="container">
        <div class="admin-panel">
            <h1>Painel Administrativo</h1>
            <div class="admin-links">
                <a href='?pg=clientes-admin'>Listar Clientes</a>
                <a href='?pg=produtos/index'>Gerenciar Produtos</a>
                <a href='logout.php' class="logout-btn">Sair</a>
            </div>

            <?php
            if(empty($_SERVER['QUERY_STRING'])){
               echo "<h3>Bem-vindo ao painel admin. Use os links acima para gerenciar o site.</h3>";
            }else {
                $pg = $_GET['pg'] ?? '';

                if (in_array($pg, $allowed_pages)) {
                    $arquivo = "$pg.php";
                    // Ajusta o caminho para arquivos na pasta 'produtos'
                    if (strpos($pg, 'produtos/') === 0) {
                        $arquivo = "../" . $arquivo;
                    }

                    if(file_exists($arquivo)){
                        include_once $arquivo;
                    } else {
                        echo "<h3>Página não encontrada</h3>";
                    }
                } else {
                    echo "<h3>Acesso negado ou página inválida!</h3>";
                }
            }
            ?>
        </div>
    </div>

    <?php include_once "../rodape.php"; // Inclui o rodapé ?>
</body>
</html>