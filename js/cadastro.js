/* Cadastro function */

const senha = document.getElementById('senha');
const confSenha = document.getElementById('confSenha');
const mensagem = document.getElementById('mensagemSenha');

function verificarSenhas() {

    if (confSenha.value === '') {
        mensagem.textContent = '';
    }
    else if (senha.value !== confSenha.value) {
        mensagem.textContent = 'As senhas não coincidem.';
        mensagem.style.color = 'red';
    }
    else {
        mensagem.textContent = 'Senhas coincidem.';
        mensagem.style.color = 'green';
    }
}

senha.addEventListener('input', verificarSenhas); /* Adiciona o evento de input ao campo de senha */
confSenha.addEventListener('input', verificarSenhas); /* Adiciona o evento de input ao campo de confirmação de senha */

/* Função para validar o formulário de cadastro */

const formCadastro = document.getElementById('formCadastro');

formCadastro.addEventListener('submit', function (event) {
    if (senha.value !== confSenha.value) {
        event.preventDefault();
    }
});

