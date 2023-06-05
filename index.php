<?php

//ini_set('display_errors', true);

session_start();
set_include_path(__DIR__ . '/includes/');

$initConfigs = [
    'pegarFotosTeuQuiz'      => $_SERVER['HTTP_HOST'] == 'tqt.teuquiz.com',
    'noAnalytics'            => $_SERVER['HTTP_HOST'] == 'tqt.teuquiz.com',
    'adblock'                => true,
    'existeNotificacao'      => false,
    'semJquery'              => true,
    'hide'                   => false,
    'semBase'                => false,
    'semFooter'              => false,
    'mostrarPopUpAliExpress' => false
];

$url = $_GET['parametroURLsite'] ?? '';

ob_start();

try {
    include 'autoload/functions.php';
    include 'autoload/config.php';
    include 'autoload/conexao.php';

    if(empty($url)){
        require_once(__DIR__ . '/controllers/IndexController.php');
        $c = new IndexController();
        $c->index();
    }else{
        $controller  = explode('/', $url)[0];
        $metodoParam = explode('/', $url)[1] ?? 'index';

        $metodo = 'METODO_DE_ERRO';
        if(!preg_match('/[A-Z]/', $metodoParam)){
            $metodo = empty($metodoParam) ? 'index' : str_replace('-', '', lcfirst(ucwords($metodoParam, "-")));
        }

        $parametros = '';

        if(mb_strtoupper($controller) === 'INDEX' || mb_strtoupper($controller) === 'ERRO'){
            header('Location: ' . SITE);
            exit;
        }

        // Verificar rotas

        // Filtrar classes proibidas antes

        $controllerAlvo = str_replace('-', '', ucwords($controller, '-')) . 'Controller';
        $pathController = __DIR__ . '/controllers/' . $controllerAlvo . '.php';

        if(!file_exists($pathController)){
            erro(404);
        }

        require_once($pathController);
        $c = new $controllerAlvo();

        if($controllerAlvo === 'RequisicaoController'){
            $metodo = 'preRequisicao';
        }

        $metodosProibidos = ['carregarConteudo'];
        if(!method_exists($c,$metodo) || in_array($metodo, $metodosProibidos)){
            if(!method_exists($c,'carregarConteudo')) {
                erro("Não conseguimos carregar a página no momento. Tente novamente.");
            }
            $parametroConteudo  = $metodoParam;
            $metodo             = 'carregarConteudo';
        }

        if(isset($parametroConteudo)){
            call_user_func_array(array($c,'carregarConteudo'), [$parametroConteudo]);
        }else{
            call_user_func_array(array($c,$metodo), [explode('/', $url)[2] ?? '']);
        }
    }
}catch(Throwable $e){
    ob_end_clean();
    require_once(__DIR__ . '/controllers/ErroController.php');
    $erro = new ErroController();
    if(get_class($e) === 'CustomException'){
        $mensagemErro = empty($e->getMessage() ?? '') ? 'Erro desconhecido...' : $e->getMessage();
    }else{
        $mensagemErro = 'Não conseguimos prosseguir com o carregamento agora... Tente novamente.<br>(Nossa equipe acabou de ser avisada sobre o problema!)<br>' . $e->getMessage();
    }
//    echo "Erro real: {$e->getMessage()}";
//    echo "<br>Erro exibido: {$mensagemErro}";
//
//    echo "<hr>{$e->getTraceAsString()}";
//
//    var_dump($e);
//
//    die('<hr>Tipo de erro = ' . get_class($e));
    $erro->error($mensagemErro);
}

