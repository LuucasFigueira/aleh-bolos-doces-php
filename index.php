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

    <?php $base = "./"; include "includes/cabecalho.php"; ?> <!-- Inclui o cabeçalho da página, que contém o logo e o botão de login -->

    <section class="hero">
        <div class="hero-conteudo">
            <h1>Bolos e doces feitos com carinho</h1>
            <p>Cada receita pensada pra deixar seu momento ainda mais doce.</p>
            <a href="pages/encomendas/encomenda.php" class="botao-cta">Fazer encomenda</a>
        </div>
    </section>

    <div class="trabalhos">
        <h2>Alguns dos nossos trabalhos</h2>
        <p class="trabalhos-subtitulo">Confira alguns bolos e doces que já saíram da nossa cozinha</p>

        <div class="galeria-wrapper">
            <button class="seta esquerda" onclick="imagemAnterior()">❮</button>

            <div class="galeria-viewport" id="galeriaViewport">
                <div class="galeria-track" id="galeriaTrack">
                    <img src="img-galeria/foto1.png" alt="Bolo de chocolate" onclick="abrirImagem(this)">
                    <img src="img-galeria/foto2.png" alt="Doces variados" onclick="abrirImagem(this)">
                    <img src="img-galeria/foto3.png" alt="Bolo decorado" onclick="abrirImagem(this)">
                    <img src="img-galeria/foto4.png" alt="Brigadeiros" onclick="abrirImagem(this)">
                </div>
            </div>

            <button class="seta direita" onclick="proximaImagem()">❯</button>
        </div>

        <div id="imagemFull" class="imagem-full" onclick="fecharImagem()">
            <img id="imagemGrande">
        </div>
    </div>
    <a href="https://wa.me/5541998298487" target="_blank" rel="noopener" class="whatsapp-flutuante" aria-label="Falar no WhatsApp">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.02 3C9.4 3 4 8.4 4 15.02c0 2.2.58 4.35 1.68 6.24L4 29l7.9-1.65a12.9 12.9 0 0 0 4.12.67h.01c6.63 0 12.02-5.4 12.02-12.02C28.05 8.4 22.66 3 16.02 3Zm0 21.98h-.01a9.9 9.9 0 0 1-5.05-1.38l-.36-.21-4.69.98 1-4.58-.24-.38a9.87 9.87 0 0 1-1.52-5.4c0-5.47 4.45-9.92 9.93-9.92 2.65 0 5.14 1.04 7.01 2.91a9.86 9.86 0 0 1 2.9 7.01c0 5.47-4.45 9.97-9.97 9.97Zm5.46-7.44c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.24-.46-2.36-1.46-.87-.78-1.46-1.74-1.63-2.04-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.06 2.88 1.21 3.08c.15.2 2.09 3.2 5.07 4.48.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35Z"/>
        </svg>
    </a>

    <script src="js/galeria.js"></script> <!-- Link JS, importando para funcionar -->
    <script src="js/modal.js"></script>

</body>

</html>
