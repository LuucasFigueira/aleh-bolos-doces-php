<?php

require_once "../includes/conexao.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Só deixa continuar se o cliente estiver logado */
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (!validarTokenCSRF()) {
    die("Token de segurança inválido. Volte e tente novamente.");
}

$email = $_SESSION['email'];
$senhaAtual = $_POST['senhaAtual'];
$novaSenha = $_POST['novaSenha'];
$confirmarSenha = $_POST['confirmarSenha'];

/* Verifica se as senhas novas coincidem */
if ($novaSenha !== $confirmarSenha) {
    header("Location: ../pages/usuario/alterarSenha.php?erro=senhas");
    exit;
}

/* Verifica o tamanho mínimo da nova senha */
if (strlen($novaSenha) < 8) {
    header("Location: ../pages/usuario/alterarSenha.php?erro=senhaP");
    exit;
}

/* Busca a senha atual (com hash) do cliente logado */
$sql = "SELECT id, senha FROM cliente WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();

/* Confirma que a senha atual digitada está certa */
if (!password_verify($senhaAtual, $row['senha'])) {
    header("Location: ../pages/usuario/alterarSenha.php?erro=atual");
    exit;
}

/* Tudo certo: salva a nova senha */
$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

$sql = "UPDATE cliente SET senha = ? WHERE id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("si", $senhaHash, $row['id']);

if (!$stmt->execute()) {
    die("Erro ao alterar a senha: " . $stmt->error);
}

header("Location: ../index.php?sucesso=senha");
exit;
