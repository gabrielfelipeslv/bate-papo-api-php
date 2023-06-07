<?php

function erro($mensagem = 'Erro inesperado ao carregar página...'){
    class CustomException extends Exception {};
    throw new CustomException($mensagem);
}