<?php

require_once(__DIR__ . "/Model.php");

class RelatorioModel extends Model
{
    // retorna as refeições que ainda estão agendadas em um determinado dia
    public function getRelatorioFaltas($day)
    {
        $query = "SELECT id_usuario, hora_solicitacao FROM refeicao WHERE id_status_ref = 1 AND data_solicitacao = ?";
        return $this->executeQuery($query, [$day]);
    }

    public function getNameById($id)
    {
        $query = "SELECT nome FROM usuario WHERE id = ?";
        $result = $this->executeQuery($query, [$id]);
        return empty($result) ? [] : $result[0];
    }

    // pega o cardapio em um determinado intervalo
    public function getCardapioByInterval($inicio, $fim)
    {
        $query = "SELECT data_refeicao as data_cardapio, dia, proteina, principal, sobremesa FROM cardapio WHERE data_refeicao BETWEEN ? AND ? AND ind_excluido = 0";
        $result = $this->executeQuery($query, [$inicio, $fim]);
        return empty($result) ? [] : $result;
    }
}
