<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Só deixa acessar essa página se o cliente estiver logado */
if (!isset($_SESSION['nome'])) {
    header("Location: ../../index.php");
    exit;
}

$erro = $_GET['erro'] ?? '';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Alterar Senha</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Alterar senha</h1>

            <?php if ($erro === 'atual') : ?>
                <p class="erro" style="color: red;"><strong>A senha atual está incorreta.</strong></p>
            <?php elseif ($erro === 'senhas') : ?>
                <p class="erro" style="color: red;"><strong>As senhas digitadas não coincidem.</strong></p>
            <?php elseif ($erro === 'senhaP') : ?>
                <p class="erro" style="color: red;"><strong>A nova senha precisa ter 8 ou mais dígitos.</strong></p>
            <?php endif; ?>

            <form action="../../actions/processarAlterarSenha.php" method="POST">

                <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCSRF(); ?>">

                <label for="senhaAtual">Senha atual:</label>
                <br>
                <input type="password" id="senhaAtual" name="senhaAtual" required>
                <br><br>

                <label for="novaSenha">Nova senha:</label>
                <br>
                <input type="password" id="novaSenha" name="novaSenha" minlength="8" required>
                <br><br>

                <label for="confirmarSenha">Confirmar nova senha:</label>
                <br>
                <input type="password" id="confirmarSenha" name="confirmarSenha" minlength="8" required>
                <br><br>

                <button type="submit">Salvar nova senha</button>

            </form>

        </div>

    </div>

    <script src="../../js/modal.js"></script>

</body>

</html>
