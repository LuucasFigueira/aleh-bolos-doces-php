<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

/* Carrega as variáveis do arquivo .env (que fica na raiz do projeto,
   junto com este arquivo) para dentro de $_ENV */
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servidor = $_ENV['DB_HOST'];
$usuario = $_ENV['DB_USER'];
$senha = $_ENV['DB_PASS'];
$banco = $_ENV['DB_NAME'];


try {
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
} catch (mysqli_sql_exception $e) {
    die("Erro na conexão! Tente novamente.");
}
