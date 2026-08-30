function calcularValor() {

    let valorTotal = 0;

    const bolo = document.getElementById("produtoBolo");
    const doces = document.getElementById("produtoDoces");

    const pesoBolo = document.getElementById("pesoBolo");
    const qtdDoces = document.getElementById("qtdDoces");

    // Valor do bolo
    if (bolo.checked && pesoBolo.value) {

        const peso = parseFloat(pesoBolo.value);

        if (peso >= 1) {
            valorTotal += peso * 54.99;
        }
    }

    // Valor dos doces
    if (doces.checked && qtdDoces.value) {

        const quantidade = parseInt(qtdDoces.value);

        if (quantidade >= 20) {
            valorTotal += quantidade * 1.49;
        }
    }

    document.getElementById("valorTotal").textContent =
        valorTotal.toFixed(2).replace(".", ",");
}


function atualizarCampos() {

    const bolo = document.getElementById("produtoBolo");
    const doces = document.getElementById("produtoDoces");

    document.getElementById("campoBolo").style.display =
        bolo.checked ? "block" : "none";

    document.getElementById("campoDoces").style.display =
        doces.checked ? "block" : "none";

    calcularValor();
}