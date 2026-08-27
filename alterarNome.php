<?php

require_once "conexao.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

/* Busca o nome atual pra já deixar preenchido no campo */
$sql = "SELECT nome FROM cliente WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$resultado = $stmt->get_result();
$cliente = $resultado->fetch_assoc();

$erro = $_GET['erro'] ?? '';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Alterar Nome</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include "cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Alterar nome</h1>

            <?php if ($erro === 'vazio') : ?>
                <p class="erro" style="color: red;"><strong>O nome não pode ficar em branco.</strong></p>
            <?php endif; ?>

            <form action="processarAlterarNome.php" method="POST">

                <label for="nome">Nome:</label>
                <br>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                <br><br>

                <button type="submit">Salvar</button>

            </form>

            <p><a href="minhaConta.php">Voltar para Minha conta</a></p>

        </div>

    </div>

    <script src="js/modal.js"></script>

</body>

</html>
