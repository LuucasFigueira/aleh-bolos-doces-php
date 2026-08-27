/* Modal function */

function abrirModal() {
    document.getElementById("modal").style.display = "flex";
}

function fecharModal() {
    document.getElementById("modal").style.display = "none";
}

let botaoAbrir = document.getElementById("bntLogin");

if (botaoAbrir) {
    botaoAbrir.onclick = function () {
        abrirModal();
    };
}


let botaoFechar = document.getElementById("fecharModal");

if (botaoFechar) {
    botaoFechar.onclick = function () {
        fecharModal();
    };
}