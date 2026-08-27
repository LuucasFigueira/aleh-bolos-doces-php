<?php

require_once "conexao.php";
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/* Recebe os dados enviados pelo formulário */
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confSenha = $_POST['confSenha'];


/* Verifica se as senhas são iguais */
if ($senha !== $confSenha) {
    header("Location: cadastro.php?erro=senhas");
    exit;
}
elseif (strlen($senha) < 8 ) {
    header("Location: cadastro.php?erro=senhaP");
    exit;
}


/* Verifica se o email já está cadastrado */
$sql = "SELECT id FROM cliente WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    header("Location: cadastro.php?erro=email");
    exit;
}


/* Criptografa a senha */
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);


/* Gera um código aleatório de 6 números */
$codigo = random_int(100000, 999999);


/* Define o tempo de validade do código: 15 minutos */
$codigoExpira = date('Y-m-d H:i:s', time() + 900);


/* Cria o cadastro no banco */
$sql = "INSERT INTO cliente 
(nome, email, senha, email_verificado, codigo_verificacao, codigo_expira) 
VALUES (?, ?, ?, 0, ?, ?)";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "sssss",
    $nome,
    $email,
    $senhaHash,
    $codigo,
    $codigoExpira
);


/* Executa o cadastro */
if (!$stmt->execute()) {
    die("Erro ao cadastrar: " . $stmt->error);
}


/* =========================================
   ENVIO DO EMAIL
   ========================================= */

$mail = new PHPMailer(true);

try {

    /* Configuração SMTP - lida do arquivo .env, não fica escrita aqui */
    $mail->isSMTP();
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
    $mail->addAddress($email, $nome);


    /* Configuração da mensagem */
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->Subject = 'Confirme seu cadastro - Aleh Bolos e Doces';

    $mail->Body = "
        <h2>Olá, $nome!</h2>

        <p>Obrigado por se cadastrar na Aleh Bolos e Doces.</p>

        <p>Seu código de confirmação é:</p>

        <h1>$codigo</h1>

        <p>Esse código é válido por <strong>15 minutos</strong>.</p>

        <p>Se você não realizou este cadastro, ignore este e-mail.</p>
    ";


    /* Envia */
    $mail->send();


    /* Depois de enviar, manda para a página de confirmação */
    header("Location: confirmarEmail.php?email=" . urlencode($email));
    exit;


} catch (Exception $e) {

    echo "O cadastro foi realizado, mas não foi possível enviar o e-mail.";
    echo "<br>";
    echo "Erro: " . $mail->ErrorInfo;

}
