<?php

require_once '../includes/conexao.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!validarTokenCSRF()) {
    header("Location: ../index.php?erro=login");
    exit;
}

$email = $_POST['email'];
$senha = $_POST['senhaC'];


/* Procura o usuário pelo e-mail */
$sql = "SELECT id, nome, senha, email_verificado 
        FROM cliente 
        WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();


/* Verifica se o e-mail existe */
if ($resultado->num_rows > 0) {

    $row = $resultado->fetch_assoc();


    /* =========================================
       E-MAIL AINDA NÃO FOI VERIFICADO
       ========================================= */

    if ($row['email_verificado'] == 0) {

        /* Gera um novo código */
        $codigo = random_int(100000, 999999);

        /* Código válido por 15 minutos */
        $codigoExpira = date(
            'Y-m-d H:i:s',
            time() + 900
        );


        /* Atualiza o código no banco */
        $sql = "UPDATE cliente
                SET codigo_verificacao = ?,
                    codigo_expira = ?
                WHERE id = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "ssi",
            $codigo,
            $codigoExpira,
            $row['id']
        );


        if (!$stmt->execute()) {
            die("Erro ao gerar novo código: " . $stmt->error);
        }


        /* =========================================
           ENVIA O NOVO CÓDIGO
           ========================================= */

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


            /* Remetente */
            $mail->setFrom(
                $_ENV['MAIL_USERNAME'],
                'Aleh Bolos e Doces'
            );


            /* Destinatário */
            $mail->addAddress(
                $email,
                $row['nome']
            );


            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $mail->Subject =
                'Novo codigo de confirmacao - Aleh Bolos e Doces';

            $mail->Body = "
                <h2>Olá, {$row['nome']}!</h2>

                <p>Seu e-mail ainda não foi confirmado.</p>

                <p>Seu novo código de confirmação é:</p>

                <h1>$codigo</h1>

                <p>Esse código é válido por
                <strong>15 minutos</strong>.</p>

                <p>Se você não solicitou este código,
                pode ignorar este e-mail.</p>
            ";


            $mail->send();


            /* Vai para a tela de confirmação */
            header(
                "Location: ../pages/auth/confirmarEmail.php?email="
                    . urlencode($email)
            );

            exit;
        } catch (Exception $e) {

            echo "Não foi possível enviar o código.";
            echo "<br>";
            echo "Erro: " . $mail->ErrorInfo;
        }

        exit;
    }


    /* =========================================
       E-MAIL CONFIRMADO → VERIFICA A SENHA
       ========================================= */

    $senhaHash = $row['senha'];


    if (password_verify($senha, $senhaHash)) {

        $_SESSION['email'] = $email;
        $_SESSION['nome'] = $row['nome'];

        header("Location: ../index.php");
        exit();
    } else {

        /* Senha errada */
        header("Location: ../index.php?erro=login");
        exit();
    }
} else {

    /* E-mail não encontrado */
    header("Location: ../index.php?erro=login");
    exit();
}
