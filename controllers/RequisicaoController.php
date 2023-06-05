<?php

class RequisicaoController
{
    public function preRequisicao(){

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            $this->matarRequisicao('Não é possível seguir...');
        }

        $url = $_REQUEST['parametroURLsite'];

        $api    = explode('/', $url)[1] ?? '';
        $metodo = explode('/', $url)[2] ?? 'index';

        $apiAlvo = str_replace('-', '', ucwords($api, '-')) . 'Api';
        $pathApi = __DIR__ . '/../apis/'. $apiAlvo . '.php';

        $metodo = empty($metodo) ? 'index' : $metodo;

        if(!file_exists($pathApi)){
            $this->matarRequisicao('Não é possível prosseguir...');
        }

        require_once($pathApi);
        $c = new $apiAlvo();

        if(!method_exists($c,$metodo)){
            $this->matarRequisicao('Não é possível continuar...');
        }

        call_user_func_array(array($c,'executarRequisicao'), [$metodo]);
    }

    private function matarRequisicao($mensagem = 'Algo deu errado aqui...'){
        echo json_encode([
           'status'   => false,
           'mensagem' => $mensagem
        ]);
        die();
    }
}