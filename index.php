<?php
$produtos = [
    [
        "id" => 1,
        "nome" => "Summer Vibes",
        "preco" => "59.90",
        "imagem" => "imagens/camiseta_verao1.jpeg"
    ],
    [
        "id" => 2,
        "nome" => "Sunwave",
        "preco" => "59.90",
        "imagem" => "imagens/camiseta_verao2.jpeg"
    ],
    [
        "id" => 3,
        "nome" => "Tropical Mood",
        "preco" => "59.90",
        "imagem" => "imagens/camiseta_verao3.jpeg"
    ],
    [
        "id" => 4,
        "nome" => "Summer Flow",
        "preco" => "59.90",
        "imagem" => "imagens/camiseta_verao4.jpeg"
    ],
    [
        "id" => 5,
        "nome" => "Ocean",
        "preco" => "59.90",
        "imagem" => "imagens/camiseta_verao5.jpeg"
    ],
    [
        "id" => 6,
        "nome" => "Chill & Sun",
        "preco" => "59.90",
        "imagem" => "imagens/camiseta_verao6.jpeg"
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Coleção Verão</title>
</head>
<body>

<h1>Coleção de Camisetas Verão</h1>

<div style="display:flex; flex-wrap:wrap; gap:20px;">

<?php foreach ($produtos as $p): ?>
    <div style="width:200px; border:1px solid #000000ff; padding:10px; text-align:center;">
        <img src="<?= $p['imagem'] ?>" alt="<?= $p['nome'] ?>" style="width:100%;">
        <h3><?= $p['nome'] ?></h3>
        <p>Preço: R$ <?= $p['preco'] ?></p>
        <p>ID: <?= $p['id'] ?></p>
    </div>
<?php endforeach; ?>

</div>

<!-- RODAPÉ -->
<footer style="width:100%; background:#111; color:#fff; padding:25px 0; margin-top:40px;">
    <div style="text-align:center;">
        <h3 style="margin:0; font-size:20px;">StarkWear</h3>
        <p style="margin:8px 0 15px;">Moda moderna e estampas exclusivas</p>

        <p style="margin:0; font-size:14px; opacity:0.7;">
            &copy; 2025 StarkWear — Todos os direitos reservados.
        </p>
    </div>
</footer>

</body>
</html>
