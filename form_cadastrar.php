<h2>Cadastrar Produto</h2>

<style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background: linear-gradient(to bottom, #6CD8C4, #F8E9C8);
        padding: 40px;
        color: #034D66;
        text-align: center;
    }

    h2 {
        font-size: 32px;
        color: #055A7A;
        margin-bottom: 25px;
    }

    form {
        background: rgba(255, 255, 255, 0.85);
        padding: 30px;
        width: 450px;
        margin: auto;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        backdrop-filter: blur(6px);
        animation: fadeIn 0.7s ease-in-out;
    }

    label {
        display: block;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 8px;
        color: #05668D;
        text-align: left;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 18px;
        border-radius: 10px;
        border: 2px solid #2EC4B6;
        font-size: 16px;
        outline: none;
        transition: 0.3s;
    }

    input[type="text"]:focus {
        border-color: #ff7b6b;
        transform: scale(1.02);
    }

    input[type="submit"] {
        background-color: #2EC4B6;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 12px;
        font-size: 18px;
        cursor: pointer;
        transition: 0.3s;
        font-weight: bold;
        margin-top: 10px;
    }

    input[type="submit"]:hover {
        background-color: #ff7b6b;
        transform: translateY(-2px);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<form action="cadastrar.php" method="post">
    <label>Nome:</label>
    <input type="text" name="nome">

    <label>Preço:</label>
    <input type="text" name="preco">

    <label>Descrição:</label>
    <input type="text" name="descricao">

    <input type="submit" value="Salvar Produto">
</form>
