<?php
require_once "../includes/conexao.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!validarTokenCSRF()) {
    die("Token de segurança inválido. Volte e tente novamente.");
}

session_destroy(); /* Destroi a sessão do usuário / Desloga */
header("Location: ../index.php");
exit();
