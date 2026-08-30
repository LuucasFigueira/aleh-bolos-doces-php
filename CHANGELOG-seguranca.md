# 🔒 Changelog de Segurança — Aleh Bolos e Doces

Registro das correções de segurança aplicadas ao sistema de autenticação e cadastro, após revisão de código.

---

## 1. Prevenção de enumeração de e-mail no cadastro

**Problema:** o cadastro (`actions/cadastroUsuario.php`) respondia de forma diferente quando o e-mail informado já existia no banco (`?erro=email`), permitindo que alguém descobrisse quais e-mails estão cadastrados testando vários em sequência.

**Correção:** quando o e-mail já existe, o sistema não revela mais isso na tela. Em vez disso:
- Gera um novo código de verificação e salva na conta já existente
- Envia um e-mail avisando que aquele endereço já possui cadastro, com um código que serve para login
- Redireciona para a **mesma página** (`confirmarEmail.php`) usada no fluxo de cadastro normal, tornando os dois casos indistinguíveis por quem está de fora

**Arquivo alterado:** `actions/cadastroUsuario.php`

---

## 2. Login automático ao confirmar código de conta já existente

**Problema:** decorrente da correção acima — era preciso um destino para quem recebeu o código de "e-mail já cadastrado" e o digitou corretamente.

**Correção:** `pages/auth/verificarEmail.php` agora verifica se a conta já estava com `email_verificado = 1` **antes** da confirmação atual:
- Se já estava verificada → trata como login (grava dados na sessão e redireciona para a página inicial)
- Se não estava verificada → trata como confirmação de cadastro normal

**Arquivo alterado:** `pages/auth/verificarEmail.php`

---

## 3. Prevenção de enumeração de e-mail na verificação de código

**Problema:** `verificarEmail.php` também vazava informação por um segundo caminho: mensagens diferentes para "usuário não encontrado", "código incorreto" e "código expirado" permitiam descobrir se um e-mail existe, mesmo sem passar pelo formulário de cadastro.

**Correção:** as três situações agora retornam a mesma mensagem genérica: **"Código inválido ou expirado."**

**Arquivo alterado:** `pages/auth/verificarEmail.php`

---

## 4. Proteção contra Session Fixation (`session_regenerate_id`)

**Problema:** o ID de sessão não era renovado no momento do login, permitindo em teoria que um atacante "plantasse" um ID de sessão conhecido na vítima antes do login, e o reaproveitasse depois que ela autenticasse.

**Correção:** `session_regenerate_id(true)` agora é chamado imediatamente após a identidade ser confirmada (senha correta, ou código correto), antes de gravar qualquer dado em `$_SESSION`. Aplicado em **dois pontos**, já que existem dois fluxos de login:

- `actions/login.php` — login tradicional com e-mail e senha
- `pages/auth/verificarEmail.php` — login via código (fluxo criado no item 2)

---

## 5. Consistência no tratamento de falha de envio de e-mail

**Problema:** se o envio do e-mail falhasse, o comportamento era diferente dependendo do fluxo (cadastro novo mostrava mensagem na tela; e-mail já existente redirecionava normalmente), o que reabria uma pequena brecha de enumeração em cenário de erro.

**Correção:** em ambos os fluxos de `actions/cadastroUsuario.php`, falha no envio de e-mail agora só é registrada via `error_log()` (não aparece para o usuário), e o redirecionamento para `confirmarEmail.php` acontece sempre, independente do resultado do envio.

**Arquivo alterado:** `actions/cadastroUsuario.php`

---

## 6. Remoção de detalhes técnicos das mensagens de erro

**Problema:** mensagens como `"Erro ao cadastrar: " . $stmt->error` e `"Erro: " . $mail->ErrorInfo` expunham detalhes internos do banco de dados ou do servidor SMTP diretamente na tela do usuário.

**Correção:** mensagens genéricas para o usuário (ex: `"Erro ao cadastrar. Tente novamente."`), sem detalhes internos.

**Arquivos alterados:** `actions/login.php`, `actions/cadastroUsuario.php`

---

## 📌 Pendências conhecidas

- Rate limiting de tentativas de login (por IP e/ou por e-mail), para mitigar força bruta
- Rotina de limpeza de cadastros nunca confirmados (`email_verificado = 0` há muito tempo)
- Validação de e-mail com `filter_var($email, FILTER_VALIDATE_EMAIL)` no back-end
