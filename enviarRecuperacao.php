<?php

require_once "conexao.php";
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];

/* Procura o cliente pelo e-mail */
$sql = "SELECT id, nome FROM cliente WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

/* IMPORTANTE: por segurança, não avisamos o usuário se o e-mail existe ou não.
   Se existir, geramos o código e enviamos o e-mail de verdade.
   Se não existir, simplesmente não fazemos nada, mas mandamos o cliente
   pra mesma tela de "digite o código", como se tivesse dado certo. */
if ($resultado->num_rows > 0) {

    $row = $resultado->fetch_assoc();

    /* Gera um código aleatório de 6 números, reaproveitando as mesmas colunas
       que já existem na tabela para o código de confirmação de e-mail */
    $codigo = random_int(100000, 999999);
    $codigoExpira = date('Y-m-d H:i:s', time() + 900); // válido por 15 minutos

    $sql = "UPDATE cliente
            SET codigo_verificacao = ?,
                codigo_expira = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $codigo, $codigoExpira, $row['id']);

    if ($stmt->execute()) {

        /* Envia o código por e-mail */
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Aleh Bolos e Doces');
            $mail->addAddress($email, $row['nome']);

            $mail->isHTML(true);

            $mail->Subject = 'Recuperação de senha - Aleh Bolos e Doces';

            $mail->Body = "
                <h2>Olá, {$row['nome']}!</h2>

                <p>Recebemos um pedido para redefinir sua senha.</p>

                <p>Seu código de recuperação é:</p>

                <h1>$codigo</h1>

                <p>Esse código é válido por <strong>15 minutos</strong>.</p>

                <p>Se você não solicitou isso, pode ignorar este e-mail.</p>
            ";

            $mail->send();

        } catch (Exception $e) {
            // Se o e-mail não puder ser enviado, mesmo assim seguimos o fluxo
            // (o cliente não vai ter o código, mas não revelamos o motivo)
        }
    }
}

/* Sempre redireciona para a mesma tela, exista ou não o e-mail */
header("Location: redefinirSenha.php?email=" . urlencode($email));
exit;
