<?php
session_start();
require_once __DIR__ . '/../../includes/conexao.php';

if (!isset($_SESSION['id'])) {
    echo '<main class="pedidos-container"><p class="sem-pedidos">Você precisa estar logado para ver seus pedidos.</p></main>';
    exit;
}

$fkCliente = $_SESSION['id'];

$sql = "SELECT id, pedido, data, observacao, valor, data_pedido FROM pedido WHERE fk_cliente = ? ORDER BY id DESC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $fkCliente);
$stmt->execute();
$resultado = $stmt->get_result();

$pedidos = [];
while ($linha = $resultado->fetch_assoc()) {
    $linha['itens'] = json_decode($linha['pedido'], true) ?? [];
    $pedidos[] = $linha;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aleh Bolos e Doces | Pedidos </title>
    <link rel="stylesheet" href="../../style.css">
</head>

<body>

    <?php $base = "../../";
    include "../../includes/cabecalho.php"; ?>

    <main class="pedidos-container">
        <h1>Meus Pedidos</h1>

        <?php if (empty($pedidos)): ?>
            <p class="sem-pedidos">Você ainda não fez nenhuma encomenda.</p>
        <?php else: ?>
            <div class="lista-pedidos">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="card-pedido">
                        <div class="card-pedido-topo">
                            <span class="pedido-numero">Pedido #<?= htmlspecialchars($pedido['id']) ?></span>
                            <span class="pedido-data">Feito em <?= htmlspecialchars(date('d/m/Y', strtotime($pedido['data']))) ?></span>
                        </div>

                        <ul class="pedido-itens">
                            <?php foreach ($pedido['itens'] as $item): ?>
                                <li>
                                    <?php if ($item['tipo'] === 'Bolo'): ?>
                                        🎂 Bolo — <?= htmlspecialchars($item['peso']) ?>kg, sabor <?= htmlspecialchars($item['sabor']) ?>
                                    <?php elseif ($item['tipo'] === 'Doces'): ?>
                                        🍬 Doces — <?= htmlspecialchars($item['quantidade']) ?> unidades, sabor <?= htmlspecialchars($item['sabor']) ?>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        
                            💲 Valor — R$<?= htmlspecialchars($pedido['valor']) ?>
                            <br><br>
                            
                            📅 Data Entrega — <?= htmlspecialchars(date('d/m/Y', strtotime($pedido['data_pedido']))) ?>
                            
                        </ul>

                        <?php if (!empty($pedido['observacao'])): ?>
                            <p class="pedido-observacao">📝 Obs: <?= htmlspecialchars($pedido['observacao']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
    <script src="../../js/modal.js"></script>
</body>

</html>