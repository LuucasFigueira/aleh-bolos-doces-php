<?php

require_once "../includes/conexao.php";
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!validarTokenCSRF()) {
    die("Token de segurança inválido. Volte e tente novamente.");
}


/* Recebe os dados enviados pelo formulário */
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confSenha = $_POST['confSenha'];


/* Verifica se as senhas são iguais */
if ($senha !== $confSenha) {
    header("Location: ../pages/auth/cadastro.php?erro=senhas");
    exit;
}
elseif (strlen($senha) < 8 ) {
    header("Location: ../pages/auth/cadastro.php?erro=senhaP");
    exit;
}


/* Verifica se o email já está cadastrado */
$sql = "SELECT id, nome, email_verificado FROM cliente WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $row = $resultado->fetch_assoc();

    /* Gera um código como se fosse um cadastro normal */
    $codigo = random_int(100000, 999999);
    $codigoExpira = date('Y-m-d H:i:s', time() + 900);

    $sql = "UPDATE cliente SET codigo_verificacao = ?, codigo_expira = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $codigo, $codigoExpira, $row['id']);
    $stmt->execute();

    /* Envia e-mail avisando que já existe conta, com o código pra entrar */
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->setFrom($_ENV['MAIL_USERNAME'], 'Aleh Bolos e Doces');
        $mail->addAddress($email, $row['nome']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Tentativa de cadastro - Aleh Bolos e Doces';
        $mail->Body = "
            <h2>Olá, {$row['nome']}!</h2>
            <p>Alguém tentou se cadastrar usando este e-mail, mas você já tem uma conta.</p>
            <p>Se foi você, use o código abaixo para entrar:</p>
            <h1>$codigo</h1>
            <p>Válido por <strong>15 minutos</strong>.</p>
            <p>Se não foi você, apenas ignore este e-mail — nenhuma alteração foi feita na sua conta.</p>
        ";
        $mail->send();
    } catch (Exception $e) {}

    /* Mesmo destino de sempre — indistinguível de um cadastro novo */
    header("Location: ../pages/auth/confirmarEmail.php?email=" . urlencode($email));
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
    die("Erro ao cadastrar. Tente novamente.");
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
    header("Location: ../pages/auth/confirmarEmail.php?email=" . urlencode($email));
    exit;


} catch (Exception $e) {

    error_log("Falha ao enviar e-mail de cadastro para $email: " . $e->getMessage());

}

header("Location: ../pages/auth/confirmarEmail.php?email=" . urlencode($email));
exit;
