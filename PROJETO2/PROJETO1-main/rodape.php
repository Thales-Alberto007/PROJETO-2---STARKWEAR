<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ano_atual = date("Y");
?>
<style>
    .footer {
        background-color: var(--fundo-mar);
        color: white;
        padding: 20px 0;
        text-align: center;
        margin-top: auto; /* Empurra o rodapé para baixo */
    }
    .footer .container {
        max-width: 960px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: flex-start;
    }
    .footer .col {
        flex: 1;
        min-width: 200px;
        margin: 10px;
        text-align: left;
    }
    .footer .brand {
        color: white;
        text-decoration: none;
        font-size: 1.5em;
        font-weight: bold;
        display: block;
        margin-bottom: 10px;
    }
    .footer .brand .logo-mark {
        color: var(--verde-mar);
    }
    .footer p {
        margin: 5px 0;
        font-size: 0.9em;
        color: rgba(255, 255, 255, 0.7);
    }
    .footer ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer ul li a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        display: block;
        padding: 5px 0;
        transition: color 0.3s ease;
    }
    .footer ul li a:hover {
        color: var(--verde-mar);
    }
    .footer .text-right {
        text-align: right;
    }
</style>

<div class="footer">
    <div class="container">
        <div class="col">
            <a href="<?= ROUTER_URL ?>?pg=conteudo" class="brand">
                <span class="logo-mark">SW</span>
                StarkWear
            </a>
            <p>© <?= htmlspecialchars($ano_atual) ?> StarkWear. Todos os direitos reservados.</p>
        </div>

        <div class="col text-right">
            <p style="font-weight: bold; margin-bottom: 10px;">Navegação</p>
            <ul>
                <li><a href="<?= ROUTER_URL ?>?pg=conteudo">Início</a></li>
                <li><a href="<?= ROUTER_URL ?>?pg=produtos/index">Produtos</a></li>
                <li><a href="<?= ROUTER_URL ?>?pg=faleconosco">Fale Conosco</a></li>
                <?php if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true): ?>
                    <li><a href="<?= ROUTER_URL ?>?pg=carrinho/index">Carrinho</a></li>
                    <li><a href="<?= ROUTER_URL ?>?pg=users/logout">Sair</a></li>
                <?php else: ?>
                    <li><a href="<?= ROUTER_URL ?>?pg=users/login">Login</a></li>
                    <li><a href="<?= ROUTER_URL ?>?pg=users/cadastro">Cadastrar</a></li>
                <?php endif; ?>
                <li><a href="<?= BASE_URL ?>/admin/">Admin</a></li> <!-- Link para o painel administrativo -->
            </ul>
        </div>
    </div>
</div>