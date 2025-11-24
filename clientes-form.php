<h2>Cadastro de Cliente</h2>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 40px;
        text-align: center;
    }

    form {
        background: #fff;
        max-width: 420px;
        margin: 0 auto;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0,0,0,0.15);
        text-align: left;
    }

    h2 {
        text-align: center;
        color: #222;
        margin-bottom: 25px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
        color: #333;
    }

    input[type=text] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #aaa;
        border-radius: 6px;
        font-size: 15px;
    }

    input[type=submit] {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    input[type=submit]:hover {
        background: #333;
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
