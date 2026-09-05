<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../../style.css"> <!-- Link CSS, importando para funcionar -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Encomenda</title>
</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <div class="encomenda">
        <div class="cartao encomenda-cartao">
            <h1>Fazer encomenda</h1>
            <p class="aviso-whatsapp"><strong>Se não tiver cadastro, finalizamos a encomenda pelo WhatsApp.</strong></p>

            <form id="formEncomenda" action="../../actions/processarEncomenda.php" method="POST" enctype="multipart/form-data" class="form-encomenda">

                <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCSRF(); ?>">

                <?php if ($logado) : ?>
                    <label>Nome:</label>
                    <p class="nome-logado"><strong><?php echo htmlspecialchars($nomeUsuario); ?></strong></p>
                    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nomeUsuario); ?>">
                <?php else : ?>
                    <label for="nome">Nome: *</label>
                    <input type="text" id="nome" name="nome" required>
                <?php endif; ?>

                <label for="telefone">Telefone: *</label>
                <input type="text" id="telefone" name="telefone" required>

                <label for="endereco">Endereço: *</label>
                <input type="text" id="endereco" name="endereco" required>

                <div class="valor-total">
                    <span>Valor estimado</span>
                    <strong>R$ <span id="valorTotal">0,00</span></strong>
                </div>

                <label>Selecione o Produto: *</label>
                <div class="produtos">
                    <label class="produto-opcao">
                        <input type="checkbox" id="produtoBolo" name="produtos[]" value="Bolo" onchange="atualizarCampos()">
                        Bolo
                    </label>

                    <label class="produto-opcao">
                        <input type="checkbox" id="produtoDoces" name="produtos[]" value="Doces" onchange="atualizarCampos()">
                        Doces
                    </label>
                </div>

                <div id="campoBolo" class="campo-produto" style="display:none;">
                    <label for="pesoBolo">Peso do bolo (kg) - mínimo 1kg: *</label>
                    <input type="number" id="pesoBolo" name="pesoBolo" min="1" value="1" step="0.5" oninput="calcularValor()">

                    <label for="saborBolo">Sabor/Recheio do bolo: *</label>
                    <input type="text" id="saborBolo" name="saborBolo" placeholder="Ex: Chocolate com morango">
                </div>

                <div id="campoDoces" class="campo-produto" style="display:none;">
                    <label for="qtdDoces">Quantidade de doces - mínimo 20 unidades: *</label>
                    <input type="number" id="qtdDoces" name="qtdDoces" min="20" value="20" step="1" oninput="calcularValor()">

                    <label for="saborDoces">Sabor dos doces: *</label>
                    <input type="text" id="saborDoces" name="saborDoces" placeholder="Ex: Brigadeiro, beijinho">
                </div>

                <p id="mensagemProduto" class="msg-erro"></p>

                <label for="data">Data desejada: *</label>
                <input type="date" id="data" name="data" required>

                <label for="observacao">Observação: - Opcional</label>
                <textarea id="observacao" name="observacao" placeholder="Alguma observação..."></textarea>

                <p id="mensagemSenha" class="msg-aviso"></p>

                <?php if ($logado) : ?>
                    <button type="submit" class="botao-cta">Enviar pedido</button>
                <?php else : ?>
                    <button type="submit" class="botao-cta">Finalizar pelo WhatsApp</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="../../js/modal.js"></script>
    <script src="../../js/encomenda.js"></script>
    <script src="../../js/calculoValor.js"></script>

</body>

</html>
