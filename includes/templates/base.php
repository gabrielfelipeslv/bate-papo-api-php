<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php

    if(isset($seoPagina) && !empty($seoPagina)){
        echo $seoPagina;
    }

    ?>

    <meta name="robots" content="noindex" />

    <link rel="apple-touch-icon" sizes="60x60" href="<?=SITE?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=SITE?>/favicon-16x16.png">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link rel="manifest" href="<?=SITE?>/site.webmanifest">
    <link rel="manifest" href="<?=SITE?>/manifest.json" />

    <meta name="msapplication-TileColor" content="#BB2222">
    <meta name="theme-color" content="#BB2222">

    <link rel="preload" href="https://teuquiz.com/arquivos/css/fontawesome-free-5.9.0-web//webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://teuquiz.com/arquivos/css/fontawesome-free-5.9.0-web/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>

    <style>
        <?php

            echo file_get_contents(__DIR__ . '/../../includes/css/base.css');
            echo file_get_contents(__DIR__ . '/../../includes/css/atendimentos.css');

            if(isset($cssPagina)){
                if(is_array($cssPagina)){
                    foreach($cssPagina as $css){
                        if(file_exists(__DIR__ . '/../../includes/css/' . $css . '.css')){
                            echo file_get_contents(__DIR__ . '/../../includes/css/' . $css . '.css');
                        }
                    }
                }else if(file_exists(__DIR__ . '/../../includes/css/' . $cssPagina . '.css')){
                    echo file_get_contents(__DIR__ . '/../../includes/css/' . $cssPagina . '.css');
                }
            }
        ?>
    </style>
    <script>
        <?php

        echo file_get_contents(__DIR__ . '/../../includes/js/base.js');

        if(isset($jsPagina)){
            if(is_array($jsPagina)){
                foreach($jsPagina as $js){
                    if(file_exists(__DIR__ . '/../../includes/js/' . $js . '.js')){
                        echo file_get_contents(__DIR__ . '/../../includes/js/' . $js . '.js');
                    }
                }
            }else if(file_exists(__DIR__ . '/../../includes/js/' . $jsPagina . '.js')){
                echo file_get_contents(__DIR__ . '/../../includes/js/' . $jsPagina . '.js');
            }
        }

        ?>
    </script>

</head>
<body class="teuquiz-principal">

    <?php
        include('templates/header.php');
    ?>

    <main>
        <div class="corpo-pagina full-corpo" id="corpo">

            <?php
            /**
             * @var string $caminhoIncludePagina
             */
            include($caminhoIncludePagina);
            ?>

        </div>
    </main>

    <?php
        include('templates/footer.php');
    ?>

</body>
</html>
