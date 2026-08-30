<?php
// Código para processar a encomenda
require_once '../includes/conexao.php';
session_start();

if (!validarTokenCSRF()) {
    die("Token de segurança inválido. Volte e tente novamente.");
}

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$produtos = $_POST['produtos'] ?? []; // array com "Bolo" e/ou "Doces"
$pedido = [];
$data = $_POST['data'];
$observacao = trim($_POST['observacao']);

$valor = 0;
$fkCliente = '';
if (isset($_SESSION['id'])) {
    $fkCliente = $_SESSION['id'];
} else {
    $fkCliente = null;
}
$dataN = date('Y-m-d H:i:s');
$codigoPedido = strtoupper(bin2hex(random_bytes(4)));


$mensagem = "🍰 *Nova encomenda*\n\n";
$mensagem .= "🔑 *Código do pedido:* $codigoPedido\n";
$mensagem .= "👤 *Nome:* $nome\n";
$mensagem .= "📱 *Telefone:* $telefone\n";
$mensagem .= "📍 *Endereço:* $endereco\n";

// Bolo
if (in_array("Bolo", $produtos)) {
    $pesoBolo = $_POST['pesoBolo'] ?? '';
    $saborBolo = $_POST['saborBolo'] ?? '';
    $mensagem .= "🎂 *Produto:* Bolo (" . $pesoBolo . "kg)\n";
    $mensagem .= "🍫 *Sabor do bolo:* $saborBolo\n";
    $valor +=  (float) $pesoBolo * 54.99;

    $pedido[] = [ // cria uma lista dentro da lista pedido para separar bolo e as informações peso - sabor
        "tipo" => "Bolo",
        "peso" => $pesoBolo ?? '',
        "sabor" => $saborBolo ?? ''
    ];
}

// Doces
if (in_array("Doces", $produtos)) {
    $qtdDoces = $_POST['qtdDoces'] ?? '';
    $saborDoces = $_POST['saborDoces'] ?? '';
    $mensagem .= "🍬 *Produto:* Doces (" . $qtdDoces . " unidades)\n";
    $mensagem .= "🍭 *Sabor dos doces:* $saborDoces\n";
    $valor +=  (float) $qtdDoces * 1.49;

    $pedido[] = [ // cria uma lista dentro da lista pedido para separar doces e as informações quantia - sabor
        "tipo" => "Doces",
        "quantidade" => $qtdDoces ?? '',
        "sabor" => $saborDoces ?? ''
    ];
}

$mensagem .= "📅 *Data desejada:* $data\n";
$mensagem .= "💲 *Valor total:* :$valor\n";

if ($observacao !== '') {
    $mensagem .= "📝 *Observação:* $observacao\n";
}

$mensagem .= "\n🖼️ *Imagem de referência:* (Enviar em baixo - Opcional)";

$numero = "5541998298487"; // coloque aqui o WhatsApp da loja

$mensagemCodificada = rawurlencode($mensagem);

$produtosBd = json_encode($pedido); // Aqui o pedido vai para o banco em json, para mostrar a lista completa bolo-sabor-quantia-peso

$sql = "INSERT INTO pedido 
(fk_cliente, pedido, data, observacao, valor, data_pedido, codigo_pedido) 
VALUES (?, ?, ?, ?, ?, ?,?)";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "isssdss",
    $fkCliente,
    $produtosBd,
    $dataN,
    $observacao,
    $valor,
    $data,
    $codigoPedido
);


/* Executa o cadastro */
if (!$stmt->execute()) {
    die("Erro ao cadastrar: " . $stmt->error);
}

/* Redirecionamento */
if (isset($_SESSION['id'])) {

    header("Location: ../pages/usuario/pedidos.php");
    exit;

} else {

    header("Location: https://wa.me/$numero?text=$mensagemCodificada");
    exit;
}
