<?php

class IndexController extends Controller
{
    public function __construct(){
        parent::__construct(css: 'index');
    }

    public function index(){
        $atendimentos = new Atendimentos();
        $this->renderizar('index', vars: [
            'atendimentos' => $atendimentos->getAtendimentosAbertosRecentes()
        ]);
    }
}