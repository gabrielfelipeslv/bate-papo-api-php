<?php

function erro($mensagem = 'Erro inesperado ao carregar página...'){
    class CustomException extends Exception {};
    throw new CustomException($mensagem);
}

function e($string = ''){
    $string = empty($string ?? '') && $string !== 0 ? 'ERRO AO CARREGAR' : $string;
    return addslashes(htmlentities($string, ENT_QUOTES, 'UTF-8'));
}

function usuarioLogado(){
    return isset($_SESSION['usuario']) && !empty($_SESSION['usuario']);
}

function gerarBlocoRecomendados($dados){
    foreach($dados as $key => $dado){
        $dados->KEY = e($dado);
    }
    $bloco = $dados;

    // Alterar apelidos para o padrão nos casos necessários
    $bloco->TIPO === 'enquetes' ? $bloco->CAPA = $bloco->YOUTUBER : false;

    if($bloco->TIPO !== 'enquetes' || $bloco->CAPA === 'foto'){
        if($bloco->TIPO !== 'enquetes' && !empty($bloco->YOUTUBER)){
            $bloco->IMAGEM = 'yt';
            $bloco->YT_CAPA = $bloco->YOUTUBER;
        }else{
            $bloco->IMAGEM = '/arquivos/imagens/' . $dados->TIPO . '/capas/' . $dados->LINK . '.webp';
        }
    }else{
        $bloco->IMAGEM    = 'texto';

        $configCapa = json_decode(htmlspecialchars_decode($bloco->CAPA), true);
        $bloco->BG_CAPA    = $configCapa['capa-fundo'] ?? '#353535';
        $bloco->COLOR_CAPA = $configCapa['capa-cor'] ?? '#FAFAFA';
        $bloco->TXT_CAPA   = $configCapa['capa'] ?? 'Erro ao carregar capa...';
    }

    if($bloco->TEXTO_TIPO == 'x'){
        $bloco->TEXTO_TIPO = ucfirst(str_replace('-', ' ', $bloco->TIPO));
        $bloco->IMAGEM = '/arquivos/imagens/quiz-testes/' . $bloco->TIPO . '/' . $dados->LINK . '.webp';
    }

    $bloco->TITLE = ucfirst(str_replace('-', ' ', $bloco->TEXTO_TIPO)) . ': ' . $bloco->TITULO;

    $bloco->LINK   = '/' . $dados->TIPO . '/' . $dados->LINK;

    if($_SERVER['HTTP_HOST'] == 'tqt.teuquiz.com' && str_starts_with($bloco->IMAGEM, '/arquivos/')){
        $bloco->IMAGEM = 'https://teuquiz.com' . $bloco->IMAGEM;
    }

    unset($bloco->ID, $bloco->REFERENCIA, $bloco->YOUTUBER, $bloco->CRIACAO, $bloco->TAGS);

    return $bloco;
}

function listatagsf($tags){
    $lista = explode(',', $tags);
    $comando = '';

    foreach($lista as $tag){
        $comando .= "LOCATE('{$tag}', tags) DESC, ";
    }

    return substr($comando, 0, -1);
}