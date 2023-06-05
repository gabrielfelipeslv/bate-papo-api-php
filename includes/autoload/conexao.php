<?php

$banco = 'batepapo_hypeone';
$usuario = 'root';
$pass = '';

try {

    $pdo = new pdo("mysql:host=localhost;dbname=$banco;charset=utf8mb4", $usuario, $pass);
    $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);

} catch (pdoexception $e){

    erro('Certifique-se de que criou a base de dados e que configurou o arquivo <u>"includes/autoload/conexao.php"</u> conforme seu ambiente de teste!');

}

