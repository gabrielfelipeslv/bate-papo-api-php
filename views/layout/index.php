<h1>Confira os atendimentos recentes que estão em abertos (máximo de 5 itens)</h1>

<?php if(empty($atendimentos)){ ?>
    <p>Não há atendimentos em aberto.</p>
<?php }else{
    foreach ($atendimentos as $atendimento){
        var_dump($atendimento);
    }
} ?>
