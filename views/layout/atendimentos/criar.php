<h1>Iniciar novo atendimento</h1>

<div>
    <input id="titulo-atendimento" placeholder="Insira o título do seu atendimento" class="input-simples" style="margin: 20px 0">
    <button id="iniciar-atendimento" class="btn-default" style="background: var(--tema-hypeone-suave); color: white">
        Iniciar atendimento
    </button>
</div>

<script>
    document.querySelector('#iniciar-atendimento').addEventListener('click', function(){
        const campoTitulo = $$('#titulo-atendimento');
        if(campoTitulo.val().length < 1){
            alert('Insira um título para seu atendimento!');
            campoTitulo.focus();
            return;
        }

        $$('body').toggleClass('processando');

        fetch("/requisicao/atendimentos/criarAtendimento",
            {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'titulo': campoTitulo.val()
                })
            })
            .then(r => r.json())
            .then(function(retorno){
                if(retorno.success){
                    window.location.assign('/atendimentos/chat/' + retorno.idAtendimento);
                }else{
                    alert(retorno.message ? retorno.message : 'Houve um problema ao criar o atendimento. Tente novamente!')
                }
            })
            .catch(function(){
                alert('Erro inesperado ao iniciar atendimento.')
            })
            .finally(function(){
                $$('body').toggleClass('processando');
            });
    })
</script>