<?php

abstract class Api extends Sql
{
    protected Array $parametrosObrigatorios = [];
    protected Array $urlsPermitidas         = [];
    protected string $funcaoInicial         = '';
    protected string $funcaoFinal           = '';
    protected string $alterador             = '';
    protected int $offset                   = 0;
    protected mixed $indexResultados        = null;
    protected mixed $resultados             = [];
    protected mixed $resultadosExtras       = [];
    protected mixed $retorno                = [];

    public function __construct(){
        parent::__construct();
    }

    private function validarRequisicao(){
        foreach($this->parametrosObrigatorios as $x){
            if(!isset($_POST[$x]) || (empty($_POST[$x]) && !(is_numeric($_POST[$x]) && (int) $_POST[$x] === 0))){
                return false;
            }
        }
        return true;
    }

    public function executarRequisicao($funcaoResultados = ''){

        // Está validando apenas aos valores setados no __construct
        // Corrigir
        if(!$this->validarRequisicao() || empty($funcaoResultados)){
            $this->exibirResultado([
                'status' => false,
                'mensagem' => 'Problema ao prosseguir'
            ]);
        }

        if(!empty($this->funcaoInicial)){
            call_user_func_array(array($this, $this->funcaoInicial), []);
        }

        // Atualizar dados utilizando a função passada
        $this->$funcaoResultados();

        if(!empty($this->funcaoFinal)){
            call_user_func_array(array($this, $this->funcaoFinal), [$this->resultados]);
        }

        if(empty($this->resultadosExtras)){
            $this->retorno = $this->resultados;
        }else{
            $this->retorno = array_merge([$this->indexResultados ?? 'resultados' => $this->resultados], $this->resultadosExtras);
        }

        $this->exibirResultado($this->retorno);
    }

    protected function exibirResultado($resultados = []){
        echo json_encode($resultados);
        die();
    }
}