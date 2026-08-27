<?php
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Esqueci a Senha</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Esqueci a senha</h1>

            <p>Digite o seu e-mail cadastrado. Se ele existir na nossa base, vamos enviar um código para redefinir sua senha.</p>

            <?php if ($erro === 'envio') : ?>
                <p class="erro" style="color: red;"><strong>Não foi possível enviar o e-mail agora. Tente novamente em alguns minutos.</strong></p>
            <?php endif; ?>

            <form action="../../actions/enviarRecuperacao.php" method="POST">

                <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCSRF(); ?>">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br><br>

                <button type="submit">Enviar código</button>

            </form>

        </div>

    </div>

    <script src="../../js/modal.js"></script>

</body>

</html>
