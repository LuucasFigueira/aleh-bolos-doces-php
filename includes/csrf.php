<?php

/**
 * Gera (ou reaproveita) o token CSRF da sessão atual e devolve o valor.
 * Deve ser chamada em toda página que tenha um <form> de ação sensível
 * (algo que grava, altera ou apaga dados).
 */
function gerarTokenCSRF()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /* Só gera um token novo se ainda não existir um na sessão.
       Assim o mesmo token continua valendo enquanto o usuário
       navega pelo site, e não muda a cada página que ele abre. */
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Confere se o token enviado no POST bate com o token guardado na sessão.
 * Deve ser chamada bem no início de todo script que processa um POST
 * de ação sensível, antes de qualquer alteração no banco.
 */
function validarTokenCSRF()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token']) || empty($_POST['csrf_token'])) {
        return false;
    }

    /* hash_equals compara as duas strings em "tempo constante",
       evitando que alguém consiga adivinhar o token comparando
       quantos milissegundos cada tentativa levou pra responder. */
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}
