<?php

class Sql
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    protected function salvarNoReturn($comando = '', $parametros = []){
        $sql = $this->pdo->prepare($comando);
        $sql->execute($parametros);
    }

    protected function salvarReturnId($comando = '', $parametros = []){
        $sql = $this->pdo->prepare($comando);
        if($sql->execute($parametros)){
            return $this->pdo->lastInsertId();
        }else{
            return false;
        }
    }

    protected function updateReturnQt($comando = '', $parametros = []){
        $sql = $this->pdo->prepare($comando);
        if($sql->execute($parametros)){
            return $sql->rowCount();
        }else{
            return false;
        }
    }

    protected function item($comando = '', $parametros = []){
        $sql = $this->pdo->prepare($comando);
        if($sql->execute($parametros)){
            return $sql->fetch(PDO::FETCH_OBJ);
        }else{
            return false;
        }
    }

    protected function itens($comando = '', $parametros = []){
        $sql = $this->pdo->prepare($comando);
        if($sql->execute($parametros)){
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }else{
            return [];
        }
    }

}