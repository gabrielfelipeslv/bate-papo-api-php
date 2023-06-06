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

    protected function itens($comando = '', $parametros = [], $qtMaxResultados = false, $offset = false){
        $addLimit = $qtMaxResultados && is_int($qtMaxResultados);
        $addOffset = $qtMaxResultados && is_int($qtMaxResultados);
        if($addLimit){
            $comando .= " LIMIT :LIMIT_PARAM";
        }
        if($addOffset){
            $comando .= " OFFSET :OFFSET_PARAM";
        }
        $sql = $this->pdo->prepare($comando);
        if($addLimit){
            $sql->bindValue(':LIMIT_PARAM', (int) $qtMaxResultados, PDO::PARAM_INT);
        }
        if($addOffset){
            $sql->bindValue(':OFFSET_PARAM', (int) $offset, PDO::PARAM_INT);
        }
        foreach($parametros as $param => $index){
            $sql->bindValue($index, $param);
        }
        if($sql->execute()){
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }else{
            return [];
        }
    }

}