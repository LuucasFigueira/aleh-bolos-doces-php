<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
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
    <title>Aleh Bolos e Doces | Excluir Conta</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Excluir minha conta</h1>

            <p>Essa ação é <strong>permanente</strong> e não pode ser desfeita. Digite sua senha para confirmar.</p>

            <?php if ($erro === 'senha') : ?>
                <p class="erro" style="color: red;"><strong>Senha incorreta.</strong></p>
            <?php endif; ?>

            <form action="../../actions/processarExcluirConta.php" method="POST"
                onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Essa ação não pode ser desfeita.');">

                <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCSRF(); ?>">

                <label for="senha">Senha:</label>
                <br>
                <input type="password" id="senha" name="senha" required>
                <br><br>

                <button type="submit" style="background-color:#c0392b; color:#fff;">Excluir minha conta</button>

            </form>

            <p><a href="minhaConta.php">Cancelar e voltar</a></p>

        </div>

    </div>

    <script src="../../js/modal.js"></script>

</body>

</html>
