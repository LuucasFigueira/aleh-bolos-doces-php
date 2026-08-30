<?php

require_once "../../includes/conexao.php";

if (!validarTokenCSRF()) {
    die("Token de segurança inválido. Volte e tente novamente.");
}

/* Recebe os dados enviados pelo formulário */
$email = $_POST['email'];
$codigo = $_POST['codigo'];

$mensagem = '';
$sucesso = false;

/* Procura o usuário pelo e-mail */
$sql = "SELECT id, nome, codigo_verificacao, codigo_expira, email_verificado 
        FROM cliente 
        WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {

    $mensagem = "Código inválido ou expirado.";
} else {

    $row = $resultado->fetch_assoc();

    /* Código incorreto OU expirado → mesma mensagem, evita vazar qual dos dois é */
    if ($codigo !== $row['codigo_verificacao'] || strtotime($row['codigo_expira']) < time()) {

        $mensagem = "Código inválido ou expirado.";
    } else {

        $jaEstavaVerificado = $row['email_verificado'] == 1;

        $sql = "UPDATE cliente SET email_verificado = 1, codigo_verificacao = NULL, codigo_expira = NULL WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $row['id']);

        if ($stmt->execute()) {
            if ($jaEstavaVerificado) {
                /* Era uma tentativa de "cadastro" numa conta que já existia → loga o usuário */
                session_start();
                session_regenerate_id(true);
                $_SESSION['email'] = $email;
                $_SESSION['nome'] = $row['nome'];
                $_SESSION['id'] = $row['id'];
                header("Location: ../../index.php");
                exit;
            } else {
                $mensagem = "E-mail confirmado com sucesso!";
                $sucesso = true;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Confirmar E-mail</title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body>

    <?php $base = "../../";
    include "../../includes/cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Confirmação de E-mail</h1>

            <p class="<?php echo $sucesso ? 'sucesso' : 'erro'; ?>" style="color: <?php echo $sucesso ? 'green' : 'red'; ?>;">
                <?php echo htmlspecialchars($mensagem); ?>
            </p>

            <?php if ($sucesso) : ?>
                <a href="../../index.php"><button>Página inicial</button></a>
            <?php else : ?>
                <a href="confirmarEmail.php?email=<?php echo urlencode($email); ?>"><button>Tentar novamente</button></a>
            <?php endif; ?>

        </div>

    </div>

    <script src="../../js/modal.js"></script>

</body>
</html>