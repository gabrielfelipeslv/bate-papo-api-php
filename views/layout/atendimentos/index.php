<h1>Histórico de atendimentos</h1>

<div id="container-atendimentos"></div>

<button id="carregar-atendimentos" class="btn-default">Carregar atendimentos anteriores</button>

<script>
    let offset = 0;
    let recentes = true;
    const containerAtendimentos = document.querySelector('#container-atendimentos');

    function adicionaAtendimento(x){
        const link = document.createElement('a');
        link.href      = "/atendimentos/chat/" + x.ID;
        link.title     = "Atendimento: " + x.TITULO;
        link.className = 'link-atendimento';

        const titulo = document.createElement('h2');
        titulo.textContent = x.TITULO;

        const disponivel = document.createElement('p');
        disponivel.textContent = x.DISPONIVEL ? 'Disponível' : 'Finalizada';
        disponivel.style.color = x.DISPONIVEL ? 'green' : 'red';

        const inicio = document.createElement('p');
        inicio.textContent = x.INICIADO_EM;

        link.append(titulo, disponivel, inicio);

        if(!x.DISPONIVEL){
            const finalizacao = document.createElement('p');
            finalizacao.textContent = x.FINALIZADO_EM;
            link.append(finalizacao);
        }

        containerAtendimentos.append(link);
    }

    function carregarHistorico(){
        $$('body').toggleClass('processando');
        recentes = false;
        fetch('/requisicao/atendimentos/listarHistorico',
            {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'offset': offset.toString()
                })
            })
            .then(r => r.json())
            .then(function(retorno){
                if(retorno.success){
                    retorno.resultados.forEach(x => adicionaAtendimento(x));
                    offset = retorno.checkpoint;
                    recentes = retorno.continuar;
                    if(!retorno.continuar){
                        $$("#carregar-atendimentos").fadeOut();
                        alert('Todos os atendimentos foram carregados!');
                    }
                }else{
                    alert(retorno.message ? retorno.message : 'Houve um problema ao buscar processar o histórico de atendimentos. Tente novamente.');
                }
            })
            .catch(function(e){
                $$("#carregar-atendimentos").fadeOut();
                recentes = false;
                alert('Erro inesperado ao buscar histórico de atendimentos.')
            })
            .finally(function(){
                $$('body').toggleClass('processando');
            });
    }

    document.querySelector("#carregar-atendimentos").addEventListener('click', function() {
        if(recentes){
            carregarHistorico();
        }
    });

    carregarHistorico();
</script>

