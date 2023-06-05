
<header id="menu-principal">
    <div class="menu">
        <div class="menu-partes">
            <button class="toggle-menu" type="button" aria-label="Alterna navegação" style="outline: none !important; font-size: 1rem" onclick="if(document.querySelector('body').classList.contains('sombra-total')){document.querySelector('body').classList.remove('sombra-total'); document.getElementById('responsiva').style.display = 'none'}else{document.querySelector('body').classList.add('sombra-total');document.getElementById('responsiva').style.display = 'block'}">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" viewBox="0 0 297 297" xml:space="preserve">
                    <g>
                        <g>
                            <g>
                                <path d="M279.368,24.726H102.992c-9.722,0-17.632,7.91-17.632,17.632V67.92c0,9.722,7.91,17.632,17.632,17.632h176.376     c9.722,0,17.632-7.91,17.632-17.632V42.358C297,32.636,289.09,24.726,279.368,24.726z"/>
                                <path d="M279.368,118.087H102.992c-9.722,0-17.632,7.91-17.632,17.632v25.562c0,9.722,7.91,17.632,17.632,17.632h176.376     c9.722,0,17.632-7.91,17.632-17.632v-25.562C297,125.997,289.09,118.087,279.368,118.087z"/>
                                <path d="M279.368,211.448H102.992c-9.722,0-17.632,7.91-17.632,17.633v25.561c0,9.722,7.91,17.632,17.632,17.632h176.376     c9.722,0,17.632-7.91,17.632-17.632v-25.561C297,219.358,289.09,211.448,279.368,211.448z"/>
                                <path d="M45.965,24.726H17.632C7.91,24.726,0,32.636,0,42.358V67.92c0,9.722,7.91,17.632,17.632,17.632h28.333     c9.722,0,17.632-7.91,17.632-17.632V42.358C63.597,32.636,55.687,24.726,45.965,24.726z"/>
                                <path d="M45.965,118.087H17.632C7.91,118.087,0,125.997,0,135.719v25.562c0,9.722,7.91,17.632,17.632,17.632h28.333     c9.722,0,17.632-7.91,17.632-17.632v-25.562C63.597,125.997,55.687,118.087,45.965,118.087z"/>
                                <path d="M45.965,211.448H17.632C7.91,211.448,0,219.358,0,229.081v25.561c0,9.722,7.91,17.632,17.632,17.632h28.333     c9.722,0,17.632-7.91,17.632-17.632v-25.561C63.597,219.358,55.687,211.448,45.965,211.448z"/>
                            </g>
                        </g>
                    </g>
                </svg>
            </button>
        </div>
        <div class="menu-partes">
            <a href="<?=SITE?>" title="Iniciar atendimento" class="btn-default">
                Página Inicial
            </a>
        </div>

    </div>

    <nav id="responsiva">
        <a href="<?=SITE?>/atendimentos/criar" title="Iniciar atendimento">
            Iniciar atendimento
        </a>
        <a href="<?=SITE?>/atendimentos/historico" title="Histórico de atendimentos">
            Histórico
        </a>
    </nav>

    <style>
        <?=file_get_contents(__DIR__ . '/../css/header.css')?>
    </style>
</header>
