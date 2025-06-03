document.addEventListener('DOMContentLoaded', () => {
    const lista = document.querySelector('.veiculos-lista');

    function ativarFormularios() {
        document.querySelectorAll('.veiculo-card form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const acao = this.querySelector('[name="solicitar"]') ? 'solicitar' : 'devolver';
                formData.append('acao', acao);

                fetch('/Dev-admn/ajax/atualiza_veiculo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.sucesso) {
                        atualizarLista(); // Atualiza os veículos após ação
                    } else {
                        alert(data.erro || 'Erro desconhecido.');
                    }
                })
                .catch(() => alert('Erro na comunicação com o servidor.'));
            });
        });
    }

    function atualizarLista() {
    lista.classList.add('loading'); // Adiciona classe visual de carregamento

    fetch('/Dev-admn/ajax/lista_veiculos.php')
        .then(res => res.json())
        .then(data => {
            if (!data.sessao_valida) {
                alert('Sua sessão foi encerrada porque você fez login em outro local.');
                window.location.href = '/Dev-admn/login';
                return;
            }
            lista.innerHTML = data.html;
            lista.classList.remove('loading');
            ativarFormularios();
        })
        .catch(() => {
            alert('Erro na comunicação com o servidor.');
            lista.classList.remove('loading');
        });
}


    ativarFormularios(); // Ativa os formulários na primeira carga
    setInterval(atualizarLista, 5000); // Atualiza a lista a cada 5s
});
