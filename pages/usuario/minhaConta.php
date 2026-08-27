<?php

require_once "../../includes/conexao.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Só deixa acessar essa página se o cliente estiver logado */
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit;
}

/* Busca os dados atualizados do cliente no banco
   (não confiamos só na sessão, porque o nome pode ter mudado) */
$sql = "SELECT nome, email, email_verificado FROM cliente WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();

$resultado = $stmt->get_result();
$cliente = $resultado->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Minha Conta</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body>

    <?php $base = "../../"; include "../../includes/cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Minha conta</h1>

            <p><strong>Nome:</strong> <?php echo htmlspecialchars($cliente['nome']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($cliente['email']); ?></p>
            <p>
                <strong>Verificado:</strong>
                <?php echo $cliente['email_verificado'] == 1 ? '✅' : '❌'; ?>
            </p>

            <br><br>

            <a href="alterarNome.php"><button>Alterar nome</button></a>
            <br><br>

            <a href="alterarSenha.php"><button>Alterar senha</button></a>
            <br><br>

            <a href="excluirConta.php"><button style="background-color:#c0392b; color:#fff;">Excluir minha conta</button></a>
            <br><br>

            <form action="../../actions/logout.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo gerarTokenCSRF(); ?>">
                <button type="submit">Sair</button>
            </form>

        </div>

    </div>

    <script src="../../js/modal.js"></script>

</body>

</html>
