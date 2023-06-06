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

    protected function listarHistorico(){
        $json = json_decode(file_get_contents('php://input'));
        if(!isset($json->offset) || !is_numeric((int) $json->offset)){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Dados ausentes para seguir com a listagem de atendimentos!',
                'offset'  => $_GET
            ]);
        }
        $this->offset = (int) $json->offset;
        $this->resultadosExtras['success'] = true;
        $this->resultados = $this->itens(
            "SELECT id, titulo, disponivel, iniciado_em,  finalizado_em
                    FROM atendimentos
                    ORDER BY id DESC", qtMaxResultados: 6, offset: $this->offset
        );

        $this->checkContinuarPaginacao();
    }

    private function checkContinuarPaginacao(){
        $this->resultadosExtras['continuar'] = false;
        if(!empty($this->resultados) && count($this->resultados) > 5){
            $this->resultadosExtras['continuar'] = true;
            $this->resultadosExtras['checkpoint'] = $this->offset + 5;
            // Dispensamos o último resultado pois ele serve apenas para verificar se precisamos fazer uma outra consulta
            array_pop($this->resultados);
        }
    }
}