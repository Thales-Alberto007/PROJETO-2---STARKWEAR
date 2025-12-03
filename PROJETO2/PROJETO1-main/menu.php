<?php
// Inicia a sessão se ainda não estiver iniciada (o index.php já faz isso, mas é bom garantir)

?>
<style>
    .menu {
        background-color: var(--azul-marinho);
        padding: 10px 0;
        color: white;
        text-align: center;
    }
    .menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
        gap: 20px; /* Espaçamento entre os itens do menu */
    }
    .menu ul li a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        padding: 5px 10px;
        transition: background-color 0.3s ease;
    }
    .menu ul li a:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
    }
    .user-info {
        margin-left: 20px;
        font-style: italic;
    }
</style>

<div class="menu">
    <ul>
        <li><a href="<?= ROUTER_URL ?>?pg=conteudo">Início</a></li>
        <li><a href="<?= ROUTER_URL ?>?pg=produtos/index">Produtos</a></li>
        <li><a href="<?= ROUTER_URL ?>?pg=faleconosco">Fale Conosco</a></li>
        <li><a href="<?= ROUTER_URL ?>?pg=carrinho/index">🛒 Carrinho</a></li>

        <?php if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true): ?>
            <li><a href="<?= ROUTER_URL ?>?pg=users/logout">Sair (<?= htmlspecialchars($_SESSION["user_nome"]) ?>)</a></li>
        <?php else: ?>
            <li><a href="<?= ROUTER_URL ?>?pg=users/login">Login</a></li>
            <li><a href="<?= ROUTER_URL ?>?pg=users/cadastro">Cadastrar</a></li>
        <?php endif; ?>
    </ul>
</div>