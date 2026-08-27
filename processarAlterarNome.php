<?php

require_once "conexao.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$nome = trim($_POST['nome']);

if ($nome === '') {
    header("Location: alterarNome.php?erro=vazio");
    exit;
}

$sql = "UPDATE cliente SET nome = ? WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ss", $nome, $_SESSION['email']);

if (!$stmt->execute()) {
    die("Erro ao alterar o nome: " . $stmt->error);
}

/* Atualiza também o nome guardado na sessão, senão o cabeçalho
   continuaria mostrando o nome antigo até o cliente logar de novo */
$_SESSION['nome'] = $nome;

header("Location: minhaConta.php");
exit;
