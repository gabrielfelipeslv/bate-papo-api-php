<?php

class AtendimentosApi extends Api{
    public function __construct(){
        parent::__construct();
        $this->parametrosObrigatorios = [];
    }

    protected function criarMensagem(){
        $json = json_decode(file_get_contents('php://input'));
        if(!isset($json->token, $json->remetente, $json->mensagem)){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Informações incompletas para criação da mensagem!'
            ]);
        }

        $atendimento = new Atendimentos();
        $idReferer = explode('?', basename($_SERVER['HTTP_REFERER']))[0];
        $atendimentoValido = $atendimento->getAtendimentoXToken($idReferer, $json->token);

        if(!$atendimentoValido || !$atendimentoValido->DISPONIVEL){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Esse chat já foi finalizado ou não existe!'
            ]);
        }

        if(!is_numeric($json->remetente) || !in_array($json->remetente, [1, 2])){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Remetente inválido!'
            ]);
        }

        $json->mensagem = trim($json->mensagem);
        if(strlen($json->mensagem) > 255){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Mensagem além do tamanho permitido (255 caracteres)!'
            ]);
        }

        $timestamp_envio = date('Y-m-d H:i:s');
        $mensagem = new Mensagens();
        $idMensagem = $mensagem->criarMensagem($idReferer, $json->remetente, $json->mensagem, $timestamp_envio);
        if($idMensagem){
            $this->resultados = [
                'success'  => true,
                'mensagem' => [
                    'CONTEUDO'   => $json->mensagem,
                    'REMETENTE'  => $json->remetente,
                    'ENVIADA_EM' => $timestamp_envio
                ]
            ];
        }else{
            $this->resultados = [
                'success' => false
            ];
        }
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

    protected function finalizarAtendimento(){
        $json = json_decode(file_get_contents('php://input'));
        if(!isset($json->token)){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Dados ausentes para continuar.'
            ]);
        }

        $atendimento = new Atendimentos();
        $idReferer = explode('?', basename($_SERVER['HTTP_REFERER']))[0];
        $atendimentoValido = $atendimento->getAtendimentoXToken($idReferer, $json->token);

        if(!$atendimentoValido){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Credenciais inválidas para encerrar o atendimento!'
            ]);
        }

        if($atendimento->finalizarAtendimento($idReferer, $json->token)){
            $this->resultados = [
                'success' => true
            ];
        }else{
            $this->resultados = [
                'success' => false,
                'message' => 'Não conseguimos atualizar o status do atendimento... Tente novamente!'
            ];
        }
    }

    protected function listarHistorico(){
        $json = json_decode(file_get_contents('php://input'));
        if(!isset($json->offset) || !is_numeric((int) $json->offset)){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Dados ausentes para seguir com a listagem de atendimentos!'
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

    protected function listarMensagens(){
        $json = json_decode(file_get_contents('php://input'));
        if(!isset($json->offset, $json->start, $json->token) ||
            !is_numeric((int) $json->offset) ||
            !is_numeric((int) $json->start)
        ){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Dados ausentes para seguir com o carregamento das mensagens!'
            ]);
        }

        $this->offset = (int) $json->offset;

        $json->start  = (int) $json->start;

        $atendimento = new Atendimentos();
        $idReferer = explode('?', basename($_SERVER['HTTP_REFERER']))[0];
        $atendimentoValido = $atendimento->getAtendimentoXToken($idReferer, $json->token);

        if(!$atendimentoValido){
            $this->exibirResultado([
                'success' => false,
                'message' => 'Credenciais inválidas para carregamento de mensagens!'
            ]);
        }

        $sqlStart = "";
        $parametros = [
            ':ID_ATENDIMENTO' => $idReferer
        ];

        if($json->start > 0){
            $sqlStart = "AND id <= :ID_MENSAGEM_START";
            $parametros[':ID_MENSAGEM_START'] = $json->start;
        }

        $this->resultadosExtras['success'] = true;
        $this->resultados = $this->itens(
            "SELECT id, conteudo, remetente, enviada_em
                    FROM mensagens 
                    WHERE id_atendimento = :ID_ATENDIMENTO {$sqlStart} 
                    ORDER BY id DESC", $parametros, 6, $this->offset > 0 ? $this->offset : false
        );

        if($json->start < 1 && count($this->resultados) > 0){
            $this->resultadosExtras['start'] = $this->resultados[0]->ID;
        }

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