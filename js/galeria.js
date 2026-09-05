/* galeria fotos - carrossel: imagem ativa em destaque, vizinhas meio transparentes */

const track = document.getElementById("galeriaTrack");
const viewport = document.getElementById("galeriaViewport");
const imagens = track.querySelectorAll("img");

let indice = 0;

function centralizarImagem() {
    const imagemAtual = imagens[indice];

    // Calcula o quanto precisa deslocar o track pra imagem ativa ficar centralizada no viewport
    const centroViewport = viewport.offsetWidth / 2;
    const centroImagem = imagemAtual.offsetLeft + imagemAtual.offsetWidth / 2;
    const deslocamento = centroViewport - centroImagem;

    track.style.transform = `translateX(${deslocamento}px)`;

    // Marca só a imagem ativa com a classe que deixa ela em destaque (opacidade e tamanho normais)
    imagens.forEach((img, i) => {
        img.classList.toggle("ativa", i === indice);
    });
}

function proximaImagem() {
    indice = (indice + 1) % imagens.length;
    centralizarImagem();
}

function imagemAnterior() {
    indice = (indice - 1 + imagens.length) % imagens.length;
    centralizarImagem();
}

function abrirImagem(img) {
    const imagemGrande = document.getElementById("imagemGrande");
    const tela = document.getElementById("imagemFull");

    imagemGrande.src = img.src;
    tela.style.display = "flex";
}

function fecharImagem() {
    document.getElementById("imagemFull").style.display = "none";
}

// Centraliza a imagem ativa assim que a página carrega e sempre que a tela mudar de tamanho
window.addEventListener("load", centralizarImagem);
window.addEventListener("resize", centralizarImagem);
