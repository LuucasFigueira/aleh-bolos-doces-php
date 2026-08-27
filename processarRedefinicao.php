<?php

require_once "conexao.php";

$email = $_POST['email'];
$codigo = $_POST['codigo'];
$novaSenha = $_POST['novaSenha'];
$confirmarSenha = $_POST['confirmarSenha'];

/* Verifica se as senhas são iguais */
if ($novaSenha !== $confirmarSenha) {
    header("Location: redefinirSenha.php?email=" . urlencode($email) . "&erro=senhas");
    exit;
}

/* Verifica o tamanho mínimo da senha */
if (strlen($novaSenha) < 8) {
    header("Location: redefinirSenha.php?email=" . urlencode($email) . "&erro=senhaP");
    exit;
}

/* Busca o cliente pelo e-mail */
$sql = "SELECT id, codigo_verificacao, codigo_expira FROM cliente WHERE email = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    // Não revela se o e-mail existe ou não, trata como código incorreto
    header("Location: redefinirSenha.php?email=" . urlencode($email) . "&erro=codigo");
    exit;
}

$row = $resultado->fetch_assoc();

/* Compara o código como texto dos dois lados, pra não dar falso "incorreto"
   caso a coluna no banco devolva o valor como número */
if ((string) $codigo !== (string) $row['codigo_verificacao'] || $row['codigo_verificacao'] === null) {
    header("Location: redefinirSenha.php?email=" . urlencode($email) . "&erro=codigo");
    exit;
}

/* Verifica se o código ainda está dentro do prazo */
if (strtotime($row['codigo_expira']) < time()) {
    header("Location: redefinirSenha.php?email=" . urlencode($email) . "&erro=expirado");
    exit;
}

/* Tudo certo: atualiza a senha e limpa o código usado */
$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

$sql = "UPDATE cliente
        SET senha = ?,
            codigo_verificacao = NULL,
            codigo_expira = NULL
        WHERE id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("si", $senhaHash, $row['id']);

if (!$stmt->execute()) {
    die("Erro ao redefinir a senha: " . $stmt->error);
}

header("Location: index.php?sucesso=senha");
exit;
