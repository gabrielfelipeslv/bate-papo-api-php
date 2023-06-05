<?php

abstract class Controller
{
    protected mixed $CSS;
    protected mixed $JS;
    protected Array $SEO;
    protected Array $CLASSCONFIGS;

    public function __construct($seo = [], $css = [], $js = [], $classConfigs = []){
        $this->CSS          = $css;
        $this->JS           = $js;
        $this->SEO          = $seo;
        $this->CLASSCONFIGS = $classConfigs;
    }

    protected function renderizarConteudo($dados = []){
        if(empty($dados)){
            erro('Erro ao carregar o conteúdo desejado...');
        }
        $dados['complementoMain'] = 'class="eqpn" itemscope itemtype="http://schema.org/Article"';
        $this->renderizar('conteudos/conteudo', $dados, []);
    }

    protected function renderizar($path = '', $vars = [], $configs = []){
        if(empty($path)){
            erro('Erro ao carregar página');
        }
        $pathCompleto = __DIR__ . '/../views/layout/' . $path . '.php';
        if(file_exists($pathCompleto)){
            $this->pagina($pathCompleto, $vars, $configs);
        }else{
            erro('Erro ao gerar página');
        }
    }

    private function pagina($caminhoIncludePagina, $vars, $configs){
//        echo "Carrega página = {$caminhoIncludePagina}<br>";
//        echo "CSS = {$this->CSS}<br>";
//        echo "JS = {$this->JS}<br>";
//        echo "SEO = ";
//        echo var_dump($this->SEO);
//
//        die();

        // Configurações default
        global $initConfigs;
        extract($initConfigs);

        // Configurações da classe
        if(isset($this->CLASSCONFIGS) && is_array($this->CLASSCONFIGS)){
            extract($this->CLASSCONFIGS);
        }

        // Configurações do método
        if(is_array($configs) && !empty($configs)){
            extract($configs);
        }

        // Variáveis parametrizadas
        if(is_array($vars) && !empty($vars)){
            extract($vars);
        }

        $cssPagina = $this->CSS;
        $jsPagina  = $this->JS;
        $seoPagina = $this->SEO;

        include('templates/base.php');
    }

    protected function renderizarTemplate($path = '', $dados = '', $amp = false){
        $pathCompleto = __DIR__ . '/../views/templates/' . $path . '.php';
        if(empty($dados) || empty($path) || !file_exists($pathCompleto)){
            echo 'Tivemos um erro ao carregar essa parte...';
            return;
        }

        extract($dados);
        include($pathCompleto);
    }

    protected function getURLConteudoComLink(){
        $refererArray = explode('/', $_SERVER['HTTP_REFERER']);
        return [$refererArray[count($refererArray) - 2], explode('?',$refererArray[count($refererArray) - 1])[0]];
    }

}