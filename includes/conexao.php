<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once __DIR__ . '/csrf.php';

use Dotenv\Dotenv;

/* Carrega as variáveis do arquivo .env, que fica na raiz do projeto, para dentro de $_ENV */
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
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
