<h2>Cadastro de Cliente</h2>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: linear-gradient(to bottom, #87d8ff, #ffe9b3);
        margin: 0;
        padding: 40px;
        text-align: center;
        min-height: 100vh;
    }

    h2 {
        text-align: center;
        color: #003b5c;
        margin-bottom: 25px;
        background: #ffffffcc;
        display: inline-block;
        padding: 10px 25px;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.25);
    }

    form {
        background: #ffffffd9;
        max-width: 420px;
        margin: 0 auto;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.20);
        text-align: left;
        backdrop-filter: blur(4px);
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 15px;
        color: #003b5c;
    }

    input[type=text] {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        border: 1px solid #88bcd4;
        border-radius: 8px;
        font-size: 15px;
        background: #f0faff;
    }

    input[type=text]:focus {
        outline: none;
        border-color: #53b8ff;
        box-shadow: 0 0 6px #53b8ff80;
    }

    input[type=submit] {
        width: 100%;
        margin-top: 25px;
        padding: 12px;
        background: #ffd37c;
        color: #5a3d0c;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 17px;
        font-weight: bold;
        box-shadow: 0 3px 8px rgba(0,0,0,0.25);
        transition: 0.2s;
    }

    input[type=submit]:hover {
        background: #ffca5c;
    }
</style>

<form action="?pg=clientes-cadastro" method="post">
    <label>Nome:</label>
    <input type="text" name="cliente" placeholder="Digite sua resposta...">

    <label>Cidade:</label>
    <input type="text" name="cidade" placeholder="Digite sua resposta...">

    <label>Estado:</label>
    <input type="text" name="estado" placeholder="Digite sua resposta...">

    <input type="submit" value="Cadastrar Cliente">
</form>
