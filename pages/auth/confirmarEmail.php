<?php

$email = $_GET['email'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Confirmar E-mail</title>

    <link rel="stylesheet" href="../../style.css">

</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Confirmar E-mail</h1>

            <p>
                Enviamos um código de confirmação para:
            </p>

            <strong>
                <?php echo htmlspecialchars($email); ?>
            </strong>

            <br><br>

            <form action="verificarEmail.php" method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?php echo gerarTokenCSRF(); ?>"
                >

                <input
                    type="hidden"
                    name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                >

                <label for="codigo">
                    Código de confirmação:
                </label>

                <br>

                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    maxlength="6"
                    required
                >

                <br><br>

                <button type="submit">
                    Confirmar E-mail
                </button>

            </form>

        </div>

    </div>

    <script src="../../js/modal.js"></script>
    
</body>

</html>
