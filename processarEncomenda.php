<?php
// Código para processar a encomenda
require_once 'conexao.php';

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$produtos = $_POST['produtos'] ?? []; // array com "Bolo" e/ou "Doces"
$data = $_POST['data'];
$observacao = trim($_POST['observacao']);

$mensagem = "🍰 *Nova encomenda*\n\n";
$mensagem .= "👤 *Nome:* $nome\n";
$mensagem .= "📱 *Telefone:* $telefone\n";
$mensagem .= "📍 *Endereço:* $endereco\n";

// Bolo
if (in_array("Bolo", $produtos)) {
    $pesoBolo = $_POST['pesoBolo'] ?? '';
    $saborBolo = $_POST['saborBolo'] ?? '';
    $mensagem .= "🎂 *Produto:* Bolo (" . $pesoBolo . "kg)\n";
    $mensagem .= "🍫 *Sabor do bolo:* $saborBolo\n";
}

// Doces
if (in_array("Doces", $produtos)) {
    $qtdDoces = $_POST['qtdDoces'] ?? '';
    $saborDoces = $_POST['saborDoces'] ?? '';
    $mensagem .= "🍬 *Produto:* Doces (" . $qtdDoces . " unidades)\n";
    $mensagem .= "🍭 *Sabor dos doces:* $saborDoces\n";
}

$mensagem .= "📅 *Data desejada:* $data\n";

if ($observacao !== '') {
    $mensagem .= "📝 *Observação:* $observacao\n";
}

$mensagem .= "\n🖼️ *Imagem de referência:* (Enviar em baixo - Opcional)";

$numero = "5541998298487"; // coloque aqui o WhatsApp da loja

$mensagemCodificada = rawurlencode($mensagem);

header("Location: https://wa.me/$numero?text=$mensagemCodificada");
exit;
