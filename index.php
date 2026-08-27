<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <link rel="stylesheet" href="style.css"> <!-- Link CSS, importando para funcionar -->
    <meta charset="UTF-8">
    <meta edge="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Início</title>

</head>

<body>

    <?php include "cabecalho.php"; ?> <!-- Inclui o cabeçalho da página, que contém o logo e o botão de login -->
    <div class="trabalhos">
        <h2>Alguns dos nossos trabalhos</h2>

        <div class="galeria">
            <button class="seta esquerda" onclick="imagemAnterior()">❮</button>
            <img id="imagemGaleria" src="img-galeria/foto1.png" alt="Bolo de chocolate"
                onclick="abrirImagem()">
            <button class="seta direita" onclick="proximaImagem()">❯</button>
        </div>

        <div id="imagemFull" class="imagem-full" onclick="fecharImagem()">
            <img id="imagemGrande">
        </div>
    </div>
    <script src="js/galeria.js"></script> <!-- Link JS, importando para funcionar -->
    <script src="js/modal.js"></script>

</body>

</html>