<h1>Confira os atendimentos disponíveis mais recentes (máximo de 5 itens)</h1>

<?php if(empty($atendimentos)){ ?>
    <p>Não há atendimentos em aberto.</p>
<?php }else{ ?>
    <div id="container-atendimentos">
    <?php foreach ($atendimentos as $atendimento){ ?>
        <a href="/atendimentos/chat/<?=$atendimento->ID?>" title="Atendimento: <?=$atendimento->TITULO?>" class="link-atendimento">
            <h2><?=$atendimento->TITULO?></h2>
            <p style="color: green;">Disponível</p>
            <p><?=date('H:i:s d/m/Y',strtotime($atendimento->INICIADO_EM))?></p></a>
    <?php } ?>
    </div>
<?php } ?>
