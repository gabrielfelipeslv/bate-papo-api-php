<?php

class AtendimentosController extends Controller
{

    public function __construct(){
        parent::__construct(
            css: ['atendimentos']
        );
    }

    public function index(){
        $this->renderizar('atendimentos/historico', configs: [
        ]);
    }

    public function criar(){
        $this->renderizar('atendimentos/criar');
    }

    public function chat($id = 0){
        $atendimento = new Atendimentos();
        $dadosAtendimento = $atendimento->getAtendimento($id);
        if(!$dadosAtendimento){
            erro('O link acessado não corresponde a nenhum atendimento existente.');
        }
        $this->renderizar('atendimentos/chat', configs: [
            'atendimento' => $dadosAtendimento
        ]);
    }

    public function carregarConteudo($urlAmigavel = ''){
        $this->JS = 'quiz-trivia';

        $conteudos     = new Conteudos();
        $dadosConteudo = $conteudos->buscarDadosConteudo('buscarQuizTrivia', $urlAmigavel, $this->TIPO_CONTEUDO);
        $dadosView = [
            'tipoConteudo'      => 'Quiz:',
            'tipoConteudoURL'   => 'quiz-trivia',
            'tipoConteudoTexto' => 'esse%20quiz',
            'pastaConteudo'     => 'quiz-trivia',
            'botao'             => [
                'texto'  => 'Fazer o teste',
                'funcao' => 'iniciar',
                'classe' => 'trivia'
            ],
            'formulario'        => [
                'destino' => 'validar-resultado'
            ]
        ];
        $this->SEO = [];

        $dados = array_merge($dadosConteudo, $dadosView);

        $this->renderizarConteudo($dados);
    }

    public function validarResultado(){
        $conteudos = new Conteudos();

        $quiz = $conteudos->buscarQuizTrivia($this->getURLConteudoComLink()[1]);
        if(!isset($_POST['campo-conteudo']) || !$quiz || $this->getURLConteudoComLink()[0] != 'quiz-trivia'){
            erro(403);
        }

        $usuario = new Usuario();
        $usuario->registrarResposta(2, $quiz->ID, $_POST['campo-conteudo'], $quiz->QT_PERGUNTAS);

        $_SESSION['respostas']['quiz-trivia'] = [];
        $_SESSION['respostas']['quiz-trivia']['link']    = $quiz->LINK;
        $_SESSION['respostas']['quiz-trivia']['acertos'] = $_POST['campo-conteudo'];

        header('Location: ' . SITE . '/quiz-trivia/resultado');
    }

    public function resultado(){
        if(!$_SESSION['respostas']['quiz-trivia']['link'] || !is_numeric($_SESSION['respostas']['quiz-trivia']['acertos'])){
            erro(403);
        }

        $conteudos = new Conteudos();
        $conteudo  = $conteudos->buscarQuizTrivia($_SESSION['respostas']['quiz-trivia']['link']);

        $acertos = (int) $_SESSION['respostas']['quiz-trivia']['acertos'];
        $textoResultado = '';

        if($acertos == 0){
            $textoResultado = $conteudo->ZERO ?? '';
        } else {
            if($acertos == $conteudo->QT_PERGUNTAS){
                $textoResultado = $conteudo->TOTAL ?? '';
            } else {
                if($acertos == $conteudo->QT_PERGUNTAS - 1){
                    $textoResultado = $conteudo->QUASE ?? '';
                } else {
                    $textoResultado = $conteudo->PADRAO ?? '';
                }
            }
        }

        if(empty($textoResultado)){
            $textoResultado = 'Esse foi seu resultado!';
        }

        $this->renderizar('conteudos/quiz-trivia/resultado', vars: [
            'conteudo'       => $conteudo,
            'acertos'        => $acertos,
            'textoResultado' => $textoResultado
        ]);
    }

}