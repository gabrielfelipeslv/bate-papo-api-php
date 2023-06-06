<?php

class Atendimentos extends Sql
{
    function getAtendimentoXToken($id_atendimento = 0, $token = 0)
    {
        return $this->item('SELECT * FROM atendimentos
                                    WHERE id = :ID_ATENDIMENTO AND token_requests = :TOKEN_RECEBIDO', [
            ':ID_ATENDIMENTO' => $id_atendimento,
            ':TOKEN_RECEBIDO' => $token
        ]);
    }

    function getAtendimento($id_atendimento = 0)
    {
        return $this->item('SELECT * FROM atendimentos
                                    WHERE id = :ID_ATENDIMENTO', [
            ':ID_ATENDIMENTO' => $id_atendimento
        ]);
    }

    function getAllAtendimentos()
    {
        return $this->itens("SELECT * FROM atendimentos ORDER BY id DESC");
    }

    function getAtendimentosAbertosRecentes()
    {
        return $this->itens("SELECT * FROM atendimentos WHERE disponivel = 1 ORDER BY id DESC LIMIT 5");
    }

    function criarAtendimento($titulo, $id_atendente = '123', $id_cliente = '987')
    {
        // O registro de ID's e/ou tokens irá variar de acordo com o sistema
        // Nesse caso, utilizarei ID's pré-definidos para gerar os atendimentos

        try {
            $randomToken = bin2hex(random_bytes(32));
        }catch (Exception $e){
            $randomToken = bin2hex(openssl_random_pseudo_bytes(32));
        }

        return $this->salvarReturnId('
                INSERT INTO atendimentos 
                (id, titulo, id_atendente, id_cliente, token_requests, iniciado_em) 
                VALUES
                (NULL, :TITULO, :ID_ATENDENTE, :ID_CLIENTE, :TOKEN_REQUESTS, :INICIADO_EM)', [
            ':TITULO'         => $titulo,
            ':ID_ATENDENTE'   => $id_atendente,
            ':ID_CLIENTE'     => $id_cliente,
            ':TOKEN_REQUESTS' => $randomToken,
            ':INICIADO_EM'    => date('Y-m-d H:i:s'),
        ]);
    }

}