<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../../style.css"> <!-- Link CSS, importando para funcionar -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Cadastro</title>
</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <?php $erro = $_GET['erro'] ?? ''; ?>

    <div class="cadastro">
        <h1>Cadastro de Usuário</h1>

        <?php if ($erro === 'email') : ?>
            <p class="erro">Este email já está cadastrado. Tente fazer login ou use outro email.</p>
        <?php elseif ($erro === 'senhas') : ?>
            <p class="erro">As senhas digitadas não coincidem. Tente novamente.</p>
        <?php endif; ?>

        <form id="formCadastro" action="../../actions/cadastroUsuario.php" method="POST">

            <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCSRF(); ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <br><br>

            <label for="confSenha">Confirmar Senha:</label>
            <input type="password" id="confSenha" name="confSenha" required>
            <br>

            <p id="mensagemSenha"></p>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <script src="../../js/cadastro.js"></script> <!-- Link JS, importando para funcionar -->
    <script src="../../js/modal.js"></script>
</body>

</html>
