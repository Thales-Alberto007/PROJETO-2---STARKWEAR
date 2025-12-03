<?php

    // Conexão com o banco de dados 'projeto1'
    // ATENÇÃO: Em um ambiente de produção, as credenciais não devem ser hardcoded aqui.
    // Considere usar variáveis de ambiente ou um gerenciador de segredos.
    $conexao = mysqli_connect("localhost:3306", "root", "", "projeto1");

    if(!$conexao){
       echo "<h2>Erro ao conectar o banco de dados</h2>";
       exit;
    }

    mysqli_set_charset($conexao, "utf8mb4");
?>