<?php

class AtendimentosApi extends Api{
    public function __construct(){
        parent::__construct();
        $this->parametrosObrigatorios = [];
    }

    protected function criarAtendimento(){
        $json = json_decode(file_get_contents('php://input'));
        if(!isset($json->titulo)){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Título não recebido para criação de atendimento!'
            ]);
        }

        $atendimento = new Atendimentos();
        $idAtendimento = $atendimento->criarAtendimento($json->titulo);
        if($idAtendimento){
            $this->resultados = [
                'success' => true,
                'idAtendimento' => $idAtendimento
            ];
        }else{
            $this->resultados = [
                'success' => false
            ];
        }
    }

    protected function enquetes(){
        $this->resultados = $this->itens(
            "SELECT id,              titulo, criacao, link, capa as youtuber,                                               'enquetes' as tipo, 'Enquete' as TEXTO_TIPO, tags FROM " . CRIADOS['enquetes'] . " WHERE publica = 1
                ORDER BY criacao DESC LIMIT 5 OFFSET " . $this->offset
        );
    }

    protected function listas(){
        $this->resultados = $this->itens(
            "SELECT id, titulo, criacao, link,  '' as youtuber,                                                'listas' as tipo,   'Lista' as TEXTO_TIPO, tags FROM " . CRIADOS['listas'] . "     WHERE publica = 1
                    ORDER BY criacao DESC LIMIT 5 OFFSET " . $this->offset
        );
    }
}