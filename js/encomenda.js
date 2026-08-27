function atualizarCampos() {
    const bolo = document.getElementById("produtoBolo").checked;
    const doces = document.getElementById("produtoDoces").checked;

    const campoBolo = document.getElementById("campoBolo");
    const campoDoces = document.getElementById("campoDoces");
    const pesoBolo = document.getElementById("pesoBolo");
    const qtdDoces = document.getElementById("qtdDoces");
    const saborBolo = document.getElementById("saborBolo");
    const saborDoces = document.getElementById("saborDoces");

    campoBolo.style.display = bolo ? "block" : "none";
    pesoBolo.required = bolo;
    saborBolo.required = bolo;
    if (!bolo) {
        pesoBolo.value = "";
        saborBolo.value = "";
    }

    campoDoces.style.display = doces ? "block" : "none";
    qtdDoces.required = doces;
    saborDoces.required = doces;
    if (!doces) {
        qtdDoces.value = "";
        saborDoces.value = "";
    }
}

document.getElementById("formEncomenda").addEventListener("submit", function (e) {
    const bolo = document.getElementById("produtoBolo").checked;
    const doces = document.getElementById("produtoDoces").checked;
    const mensagemProduto = document.getElementById("mensagemProduto");

    if (!bolo && !doces) {
        e.preventDefault();
        mensagemProduto.textContent = "Selecione ao menos um produto (Bolo ou Doces).";
        return;
    }

    mensagemProduto.textContent = "";
});
