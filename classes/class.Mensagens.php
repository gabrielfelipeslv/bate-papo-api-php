<?php

class Mensagens extends Sql
{
    function getUltimaMensagem($id_atendimento = 0)
    {
        if(!$id_atendimento){
            return false;
        }
        return $this->item('SELECT * FROM mensagens
                                    ORDER BY id DESC
                                    WHERE id_atendimento = :ID_ATENDIMENTO LIMIT 1', [
            ':ID_ATENDIMENTO' => $id_atendimento
        ]);
    }

    function getAllMensagens($id_atendimento = 0)
    {
        if(!$id_atendimento){
            return [];
        }
        return $this->itens("SELECT * FROM mensagens
                                    ORDER BY id DESC
                                    WHERE id_atendimento = :ID_ATENDIMENTO LIMIT 1", [
            ':ID_ATENDIMENTO' => $id_atendimento
        ]);
    }

    function criarMensagem($id_atendimento, $remetente, $conteudo, $timestamp)
    {
        // Remetente:
        //  1 -> Atendente
        //  2 -> Usuário

        // Esse controle também varia de acordo com a regra de negócio do sistema, aqui abordei
        // uma regra simples para exemplo

        return $this->salvarReturnId('
                INSERT INTO mensagens 
                (id, id_atendimento, remetente, conteudo, enviada_em) 
                VALUES
                (NULL, :ID_ATENDIMENTO, :REMETENTE, :CONTEUDO, :ENVIADA_EM)', [
            ':ID_ATENDIMENTO' => $id_atendimento,
            ':REMETENTE'      => $remetente,
            ':CONTEUDO'       => $conteudo,
            ':ENVIADA_EM'     => $timestamp,
        ]);
    }

}