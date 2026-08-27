<?php

require_once "conexao.php";

/* Recebe os dados enviados pelo formulário */
$email = $_POST['email'];
$codigo = $_POST['codigo'];

$mensagem = '';
$sucesso = false;

/* Procura o usuário pelo e-mail */
$sql = "SELECT id, codigo_verificacao, codigo_expira 
        FROM cliente 
        WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

/* Verifica se encontrou o usuário */
if ($resultado->num_rows === 0) {

    $mensagem = "Usuário não encontrado.";

} else {

    $row = $resultado->fetch_assoc();
    $codigoBanco = (string) $row['codigo_verificacao'];

    /* Verifica se o código está correto */
    if ($codigo !== $codigoBanco) {

        $mensagem = "Código incorreto.";

    } elseif (strtotime($row['codigo_expira']) < time()) {
        /* Verifica se o código ainda está dentro do prazo */

        $mensagem = "O código expirou. Solicite um novo código.";

    } else {

        /* Confirma o e-mail */
        $sql = "UPDATE cliente 
                SET email_verificado = 1,
                    codigo_verificacao = NULL,
                    codigo_expira = NULL
                WHERE id = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $row['id']);

        if (!$stmt->execute()) {

            $mensagem = "Erro ao confirmar o e-mail.";

        } else {

            /* Tudo certo */
            $mensagem = "E-mail confirmado com sucesso!";
            $sucesso = true;

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
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include "cabecalho.php"; ?>

    <div class="cadastro">

        <div class="cartao">

            <h1>Confirmação de E-mail</h1>

            <p class="<?php echo $sucesso ? 'sucesso' : 'erro'; ?>" style="color: <?php echo $sucesso ? 'green' : 'red'; ?>;">
                <?php echo htmlspecialchars($mensagem); ?>
            </p>

            <?php if ($sucesso) : ?>
                <a href="index.php"><button>Página inicial</button></a>
            <?php else : ?>
                <a href="confirmarEmail.php?email=<?php echo urlencode($email); ?>"><button>Tentar novamente</button></a>
            <?php endif; ?>

        </div>

    </div>

    <script src="js/modal.js"></script>

</body>

</html>
