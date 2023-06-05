<?php

class ErroController extends Controller
{
    public function __construct(){
        parent::__construct(
            css: 'error',
            classConfigs: [
                'semBase' => true
            ]
        );
    }

    public function error($mensagem = 'Erro inesperado...'){
        if($mensagem == 404){
            header("HTTP/1.1 404 Not Found");
            $mensagem = 'Página não encontrada...';
        }else if($mensagem == 403){
            header('HTTP/1.0 403 Forbidden');
            $mensagem = 'Não conseguimos acessar a página no momento...';
        }
        $this->renderizar('error', vars: [
                'mensagemErroCustom' => $mensagem
        ]);
    }
}