<h1>Atendimento: <?=$atendimento->TITULO?> (#<?=$atendimento->ID?>) | VISÃO DO CHAT = <?=($remetente < 2 ? 'ATENDENTE' : 'CLIENTE')?></h1>

<a href="/atendimentos/chat/<?=($remetente < 2 ? 'cliente' : 'atendente')?>/<?=$atendimento->ID?>" target="_blank" id="alternar-visao-chat">
    Abrir chat do <?=($remetente < 2 ? 'cliente' : 'atendente')?> em outra aba
    <svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" mirror-in-rtl="true">
        <path d="M12.1.6a.944.944 0 0 0 .2 1.04l1.352 1.353L10.28 6.37a.956.956 0 0 0 1.35 1.35l3.382-3.38 1.352 1.352a.944.944 0 0 0 1.04.2.958.958 0 0 0 .596-.875V.96a.964.964 0 0 0-.96-.96h-4.057a.958.958 0 0 0-.883.6z"/>
        <path d="M14 11v5a2.006 2.006 0 0 1-2 2H2a2.006 2.006 0 0 1-2-2V6a2.006 2.006 0 0 1 2-2h5a1 1 0 0 1 0 2H2v10h10v-5a1 1 0 0 1 2 0z"/>
    </svg>
</a>

<h2 class="horarios-atendimento"><span style="color: darkgreen">Iniciado em:</span> <?=date('H:i:s d/m/Y',strtotime($atendimento->INICIADO_EM))?></h2>

<?php if($atendimento->DISPONIVEL){ ?>
    <button id="finalizar-atendimento" class="btn-default">
        Finalizar atendimento
    </button>
<?php }else{ ?>
    <h2 class="horarios-atendimento"><span style="color: darkred">Finalizado em:</span> <?=date('H:i:s d/m/Y',strtotime($atendimento->FINALIZADO_EM))?></h2>
<?php } ?>

<div id="container-chat">
    <p id="atendimento-iniciado" style="display: none">Atendimento iniciado</p>
    <button id="carregar-mensagens" class="btn-default">Carregar mensagens anteriores</button>
    <div id="mensagens"></div>
</div>

<div id="simulador-chat">
    <?php if($atendimento->DISPONIVEL){ ?>
        <div id="atendente" style="margin-bottom: 30px">
            <input type="text" maxlength="255" id="mensagem-input" placeholder="Simular mensagem do <?=($remetente < 2 ? 'atendente' : 'cliente')?>" class="input-simples">
            <button id="enviar-mensagem">
                Enviar
            </button>
        </div>
    <?php } ?>
</div>

<script>
    document.querySelector('#corpo').classList.add('corpo-custom-chat');
    let offset = 0;
    let recentes = true;
    let qtScrollPosLoading = 0;

    // Para manter um ponto específico para a função de mensagens anteriores, determino um ponto de partida.
    //
    // O ideal seria armazenar um token, nesse caso vou armazenar o ID da mensagem mais recente no momento
    //       do carregamento inicial da página para facilitar a compreensão do fluxo.
    let mensagemStart = 0;

    const token = '<?=$atendimento->TOKEN_REQUESTS?>';
    const containerChat        = $$('#container-chat')[0];
    const containerMensagens   = $$('#mensagens')[0];
    const btnCarregarMensagens = $$('#carregar-mensagens')[0];

    function adicionaMensagem(x, novaMensagem = true){
        const mensagem = document.createElement('div');
        mensagem.className = 'mensagem-chat ' + (x.REMETENTE == '<?=$remetente?>' ? "mensagem-propria" : "mensagem-recebida");

        const conteudo = document.createElement('p');
        conteudo.textContent = x.CONTEUDO;

        const envio = document.createElement('p');
        envio.textContent = x.ENVIADA_EM;

        mensagem.append(conteudo, envio);

        containerMensagens.insertAdjacentHTML((novaMensagem ? 'beforeend' : 'afterbegin'), mensagem.outerHTML);

        qtScrollPosLoading += containerMensagens.firstChild.offsetHeight;
    }

    function carregarMensagens(){
        qtScrollPosLoading = 0;
        $$('body').toggleClass('processando');
        recentes = false;
        fetch('/requisicao/atendimentos/listarMensagens',
            {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'token' : token,
                    'offset': offset.toString(),
                    'start' : mensagemStart.toString(),
                })
            })
            .then(r => r.json())
            .then(function(retorno){
                if(retorno.success){
                    retorno.resultados.forEach(x => adicionaMensagem(x, false));
                    offset = retorno.checkpoint;
                    recentes = retorno.continuar;
                    if(!retorno.continuar){
                        $$("#carregar-mensagens").css('display', 'none');
                        $$("#atendimento-iniciado").css('display', 'block');
                        qtScrollPosLoading += $$("#atendimento-iniciado")[0].offsetHeight;
                    }else{
                        qtScrollPosLoading += $$("#carregar-mensagens")[0].offsetHeight;
                        if(retorno.start){
                            mensagemStart = retorno.start;
                        }
                    }
                    containerChat.scrollTo(0, qtScrollPosLoading);
                    qtScrollPosLoading = 0;
                }else{
                    alert(retorno.message ? retorno.message : 'Houve um problema ao buscar processar o histórico de mensagens. Tente novamente.');
                }
            })
            .catch(function(e){
                $$("#carregar-mensagens").css('display', 'none');
                if(offset < 1){
                    containerChat.innerHTML = '';
                }
                $$("#carregar-mensagens").css('display', 'none');
                recentes = false;
                alert('Erro inesperado ao buscar histórico de mensagens.')
            })
            .finally(function(){
                $$('body').toggleClass('processando');
            });
    }

    document.querySelector("#carregar-mensagens").addEventListener('click', function() {
        if(recentes){
            carregarMensagens();
        }
    });

    carregarMensagens();

    <?php if($atendimento->DISPONIVEL){ ?>
        // Funções exclusivas para atendimentos disponíveis

        document.querySelector('#enviar-mensagem').addEventListener('click', function() {
            const campoMensagem = $$('#mensagem-input');
            if (campoMensagem.val().length < 1) {
                alert('Insira o texto de sua mensagem!');
                campoMensagem.focus();
                return;
            }
            enviarMensagem();
        });

        function sincronizarChat(){
            console.log('')
            console.log('--- Iniciando sincronização ---')
            fetch("/requisicao/atendimentos/criarMensagem",
                {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'token'    : token,
                        'remetente': '<?=$remetente?>',
                        'mensagem' : campoMensagem.val()
                    })
                })
                .then(r => r.json())
                .then(function(retorno){
                    console.log('--- Retorno recebido ---')
                    if(retorno.success){
                        campoMensagem[0].value = '';
                        adicionaMensagem(retorno.mensagem);
                        containerChat.scrollTo(0, containerChat.scrollHeight);
                    }else{
                        alert(retorno.message ? retorno.message : 'Houve um problema ao enviar sua mensagem. Tente novamente!')
                    }
                })
                .catch(function(){
                    alert('Erro inesperado ao enviar sua mensagem. Tente novamente.')
                })
                .finally(function(){
                    console.log('--- Sincronizado ---')
                    console.log('')
                });
        }

        function enviarMensagem(){
            $$('body').toggleClass('processando');

            const campoMensagem = $$('#mensagem-input');

            fetch("/requisicao/atendimentos/criarMensagem",
                {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'token'    : token,
                        'remetente': '<?=$remetente?>',
                        'mensagem' : campoMensagem.val()
                    })
                })
                .then(r => r.json())
                .then(function(retorno){
                    if(retorno.success){
                        campoMensagem[0].value = '';
                        adicionaMensagem(retorno.mensagem);
                        containerChat.scrollTo(0, containerChat.scrollHeight);
                    }else{
                        alert(retorno.message ? retorno.message : 'Houve um problema ao enviar sua mensagem. Tente novamente!')
                    }
                })
                .catch(function(){
                    alert('Erro inesperado ao enviar sua mensagem. Tente novamente.')
                })
                .finally(function(){
                    $$('body').toggleClass('processando');
                });
        }

        document.querySelector('#finalizar-atendimento').addEventListener('click', function() {
            if(confirm('Realmente deseja finalizar esse atendimento?')){
                $$('body').toggleClass('processando');

                fetch("/requisicao/atendimentos/finalizarAtendimento",
                    {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            'token': token
                        })
                    })
                    .then(r => r.json())
                    .then(function(retorno){
                        if(retorno.success){
                            alert('Atendimento finalizado!');
                            window.location.reload();
                        }else{
                            alert(retorno.message ? retorno.message : 'Houve um problema ao finalizar o atendimento. Tente novamente!')
                        }
                    })
                    .catch(function(){
                        alert('Erro inesperado ao finalizar esse atendimento. Tente novamente.')
                    })
                    .finally(function(){
                        $$('body').toggleClass('processando');
                    });
            }
        });
    <?php } ?>
</script>
