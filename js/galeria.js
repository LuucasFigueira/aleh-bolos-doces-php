/* galeria fotos */

const imagens = [
    "img-galeria/foto1.png",
    "img-galeria/foto2.png",
    "img-galeria/foto3.png",
    "img-galeria/foto4.png"
];

let indice = 0;

function proximaImagem() {
    indice++;

    if (indice >= imagens.length) {
        indice = 0;
    }

    document.getElementById("imagemGaleria").src = imagens[indice];
}

function imagemAnterior() {
    indice--;

    if (indice < 0) {
        indice = imagens.length - 1;
    }

    document.getElementById("imagemGaleria").src = imagens[indice];
}

function abrirImagem() {
    const imagem = document.getElementById("imagemGaleria");
    const imagemGrande = document.getElementById("imagemGrande");
    const tela = document.getElementById("imagemFull");

    imagemGrande.src = imagem.src;
    tela.style.display = "flex";
}

function fecharImagem() {
    document.getElementById("imagemFull").style.display = "none";
}