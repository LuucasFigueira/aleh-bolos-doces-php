<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="style.css"> <!-- Link CSS, importando para funcionar -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Encomenda</title>
</head>

<body>

    <?php include "cabecalho.php"; ?>
    <?php
    // $logado e $nomeUsuario já vêm definidos pelo cabecalho.php
    ?>

    <div class="encomenda">
        <h1>Encomenda</h1>
        <form id="formEncomenda" action="processarEncomenda.php" method="POST" enctype="multipart/form-data">

            <?php if ($logado) : ?>
                <label>Nome:</label>
                <p><strong><?php echo htmlspecialchars($nomeUsuario); ?></strong></p>
                <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nomeUsuario); ?>">
            <?php else : ?>
                <label for="nome">Nome: *</label>
                <input type="text" id="nome" name="nome" required>
            <?php endif; ?>
            <br>

            <label for="telefone">Telefone: *</label>
            <input type="text" id="telefone" name="telefone" required>
            <br>

            <label for="endereco">Endereço: *</label>
            <input type="text" id="endereco" name="endereco" required>
            <br>

            <label>Produto: *</label>
            <div class="produtos">
                <label for="produtoBolo">Bolo</label>
                <input type="checkbox" id="produtoBolo" name="produtos[]" value="Bolo" onchange="atualizarCampos()">
                

                <input type="checkbox" id="produtoDoces" name="produtos[]" value="Doces" onchange="atualizarCampos()">
                <label for="produtoDoces">Doces</label>
            </div>
            <br>

            <div id="campoBolo" style="display:none;">
                <label for="pesoBolo">Peso do bolo (kg) - mínimo 1kg: *</label>
                <input type="number" id="pesoBolo" name="pesoBolo" min="1" step="0.5">
                <br>

                <label for="saborBolo">Sabor/Recheio do bolo: *</label>
                <input type="text" id="saborBolo" name="saborBolo" placeholder="Ex: Chocolate com morango">
                <br>
            </div>

            <div id="campoDoces" style="display:none;">
                <label for="qtdDoces">Quantidade de doces - mínimo 20 unidades: *</label>
                <input type="number" id="qtdDoces" name="qtdDoces" min="20" step="1">
                <br>

                <label for="saborDoces">Sabor dos doces: *</label>
                <input type="text" id="saborDoces" name="saborDoces" placeholder="Ex: Brigadeiro, beijinho">
                <br>
            </div>

            <p id="mensagemProduto" style="color:red;"></p>

            <label for="data">Data desejada: *</label>
            <input type="date" id="data" name="data" required>
            <br>

            <label for="observacao">Observação: - Opcional</label>
            <textarea id="observacao" name="observacao" placeholder="Alguma observação..."></textarea>
            <br>

            <p id="mensagemSenha"></p>
            <button type="submit">Enviar pedido</button>
        </form>
    </div>

    <script src="js/modal.js"></script>
    <script src="js/encomenda.js"></script>

</body>

</html>
