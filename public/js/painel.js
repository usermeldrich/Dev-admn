document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.veiculo-card form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const acao = this.querySelector('[name="solicitar"]') ? 'solicitar' : 'devolver';
            formData.append('acao', acao);

            fetch('/Dev-admn/app/ajax/atualiza_veiculo.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    atualizarLista(); // Atualiza os dados após ação
                } else {
                    alert(data.erro || 'Erro desconhecido.');
                }
            })
            .catch(err => alert('Erro na comunicação com o servidor.'));
        });
    });

    function atualizarLista() {
        fetch('/Dev-admn/app/ajax/lista_veiculos.php')
            .then(res => res.text())
            .then(html => {
                document.querySelector('.veiculos-lista').innerHTML = html;
                // Reativa os eventos dos novos botões
                document.querySelectorAll('.veiculo-card form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        const acao = this.querySelector('[name="solicitar"]') ? 'solicitar' : 'devolver';
                        formData.append('acao', acao);

                        fetch('/Dev-admn/app/ajax/atualiza_veiculo.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => atualizarLista());
                    });
                });
            });
    }

    // Atualiza automaticamente a lista a cada 10 segundos
    setInterval(() => {
        atualizarLista();
    }, 10000);
});
