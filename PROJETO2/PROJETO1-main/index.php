<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão para gerenciamento de login de clientes
}

// Define a constante para o caminho base do diretório do projeto
define('BASE_URL', str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])));
// Define a constante para o caminho do roteador principal (index.php)
define('ROUTER_URL', BASE_URL . '/index.php');
// Define a constante para o URL completo do roteador principal, incluindo esquema e host
define('FULL_ROUTER_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . ROUTER_URL);

include_once "topo.php";
include_once "menu.php";

// Lista de páginas permitidas para inclusão no frontend (whitelist)
$allowed_frontend_pages = [
    "conteudo",
    "produtos/index",
    "produtos/detalhes",
    "faleconosco",
    "users/login",
    "users/cadastro",
    "users/logout",
    "carrinho/index",
    "carrinho/add",
    "pedidos/checkout",
    "pedidos/avaliar"
];

// Mensagem de feedback (se houver)
$message = $_GET['message'] ?? '';
$message_type = $_GET['message_type'] ?? '';

// área de conteúdo
echo '<div class="main-content-area">'; // Abrindo uma div para o conteúdo principal

if (!empty($message)) {
    echo '<div class="message-box ' . htmlspecialchars($message_type) . '">';
    echo htmlspecialchars($message);
    echo '</div>';
}

if(empty($_SERVER['QUERY_STRING'])){
    $pg = "conteudo";
    include_once "$pg.php";
}elseif(isset($_GET['pg'])) {
    $pg = $_GET['pg'] ?? ''; // Usa operador de coalescência nula para evitar notices

    if (in_array($pg, $allowed_frontend_pages)) {
        $arquivo = "$pg.php";

        // Ajusta o caminho para arquivos em subpastas
        if (strpos($pg, 'produtos/') === 0 || strpos($pg, 'users/') === 0 || strpos($pg, 'carrinho/') === 0 || strpos($pg, 'pedidos/') === 0) {
            // O caminho já está correto para inclusão, pois o include_once trata o relativo a partir de index.php
            // Ex: "users/login.php" já vai para a pasta users. Não precisa de ../
        }

        if(file_exists($arquivo)){
            include_once $arquivo;
        } else {
            echo "<h2 style=\"text-align:center; color: red;\">Página não encontrada</h2>";
        }
    } else {
        echo "<h2 style=\"text-align:center; color: red;\">Acesso negado ou página inválida!</h2>";
    }
}else{
    echo "<h2 style=\"text-align:center; color: red;\">Página não encontrada</h2>";
}

echo '</div>'; // Fechando a div do conteúdo principal

include_once "rodape.php";
?>
<style>
    .main-content-area {
        padding: 20px;
        flex: 1; /* Permite que a área de conteúdo se expanda */
    }
    .message-box {
        padding: 10px;
        margin: 10px auto;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        max-width: 800px; /* Limita a largura da mensagem */
    }
    .message-box.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .message-box.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .message-box.warn {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }
</style>