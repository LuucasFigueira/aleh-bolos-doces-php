<?php
$email = $_GET['email'] ?? '';
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Redefinir Senha</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include "cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Redefinir senha</h1>

            <p>Digite o código que enviamos para:</p>
            <strong><?php echo htmlspecialchars($email); ?></strong>

            <?php if ($erro === 'codigo') : ?>
                <p class="erro" style="color: red;"><strong>Código incorreto.</strong></p>
            <?php elseif ($erro === 'expirado') : ?>
                <p class="erro" style="color: red;"><strong>Esse código expirou. Solicite um novo.</strong></p>
            <?php elseif ($erro === 'senhas') : ?>
                <p class="erro" style="color: red;"><strong>As senhas digitadas não coincidem.</strong></p>
            <?php elseif ($erro === 'senhaP') : ?>
                <p class="erro" style="color: red;"><strong>A senha precisa ter 8 ou mais dígitos.</strong></p>
            <?php endif; ?>

            <form action="processarRedefinicao.php" method="POST">

                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <label for="codigo">Código de recuperação:</label>
                <br>
                <input type="text" id="codigo" name="codigo" maxlength="6" required>
                <br><br>

                <label for="novaSenha">Nova senha:</label>
                <br>
                <input type="password" id="novaSenha" name="novaSenha" minlength="8" required>
                <br><br>

                <label for="confirmarSenha">Confirmar nova senha:</label>
                <br>
                <input type="password" id="confirmarSenha" name="confirmarSenha" minlength="8" required>
                <br><br>

                <button type="submit">Redefinir senha</button>

            </form>

            <p><a href="esqueciSenha.php">Pedir um novo código</a></p>

        </div>

    </div>

    <script src="js/modal.js"></script>

</body>

</html>
