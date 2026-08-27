<?php

require_once "conexao.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$senha = $_POST['senha'];

/* Busca o hash da senha atual do cliente */
$sql = "SELECT senha FROM cliente WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();

$resultado = $stmt->get_result();
$cliente = $resultado->fetch_assoc();

/* Confirma que a senha digitada está certa antes de excluir */
if (!password_verify($senha, $cliente['senha'])) {
    header("Location: excluirConta.php?erro=senha");
    exit;
}

$sql = "DELETE FROM cliente WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);

if (!$stmt->execute()) {
    die("Erro ao excluir a conta: " . $stmt->error);
}

/* Encerra a sessão, já que o cliente não existe mais no banco */
session_destroy();

header("Location: index.php");
exit;
