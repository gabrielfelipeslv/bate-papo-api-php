<h1>Atendimento: <?=$atendimento->TITULO?> (#<?=$atendimento->ID?>)</h1>

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

<?php if($atendimento->DISPONIVEL){ ?>
    <div id="simulador-chat">
        <div id="atendente" style="margin-bottom: 15px">
            <input type="text" maxlength="255" id="mensagem-atendente" placeholder="Simular mensagem do atendente" class="input-simples">
            <button id="enviar-mensagem-atendente">
                Enviar
            </button>
        </div>
        <div id="cliente">
            <input type="text" maxlength="255" id="mensagem-cliente" placeholder="Simular mensagem do cliente" class="input-simples">
            <button id="enviar-mensagem-cliente">
                Enviar
            </button>
        </div>
    </div>
<?php } ?>

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
        mensagem.className = 'mensagem-chat ' + (x.REMETENTE > 1 ? "cliente" : "atendente");

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

        document.querySelector('#enviar-mensagem-atendente').addEventListener('click', function() {
            const campoMensagem = $$('#mensagem-atendente');
            if (campoMensagem.val().length < 1) {
                alert('Insira o texto de sua mensagem!');
                campoMensagem.focus();
                return;
            }
            enviarMensagem(1);
        });

        document.querySelector('#enviar-mensagem-cliente').addEventListener('click', function() {
            const campoMensagem = $$('#mensagem-cliente');
            if (campoMensagem.val().length < 1) {
                alert('Insira o texto de sua mensagem!');
                campoMensagem.focus();
                return;
            }
            enviarMensagem(2);
        });

        function enviarMensagem(remetente){
            $$('body').toggleClass('processando');

            const campoMensagem = remetente > 1 ? $$('#mensagem-cliente') : $$('#mensagem-atendente');

            fetch("/requisicao/atendimentos/criarMensagem",
                {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'token'    : token,
                        'remetente': remetente.toString(),
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
