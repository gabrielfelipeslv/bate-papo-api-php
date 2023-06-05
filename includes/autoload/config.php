<?php

date_default_timezone_set('America/Sao_Paulo');

function autoload($nomeClasse)
{
    $classeFormatada = ucfirst($nomeClasse);
    if(file_exists(__DIR__.'/../../classes/class.'.$classeFormatada.'.php')){
        require(__DIR__.'/../../classes/class.'.$classeFormatada.'.php');
        return;
    }

    throw new Exception("A página não pode ser carregada no momento.");
}

spl_autoload_register('autoload');

define('SITE','http://'.$_SERVER['HTTP_HOST']);