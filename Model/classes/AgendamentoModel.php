<?php

require_once(__DIR__ . "/Model.php");

class AgendamentoModel extends Model
{
    public function hasAgendamento($dia, $idUser)
    {
        $query = "SELECT COUNT(*) AS total FROM refeicao WHERE id_usuario = ? AND data_solicitacao = ? AND id_status_ref = 1";
        $resultado = $this->executeQuery($query, [$idUser, $dia]);
        if ($resultado === false) { return false; }
        return isset($resultado[0]['total']) && $resultado[0]['total'] > 0;
    }

    public function retirarAlmoco($dia, $idUser)
    {
        $query = "UPDATE refeicao SET id_status_ref = 3 WHERE id_usuario = ? AND data_solicitacao = ? AND id_status_ref = 1";
        return $this->executeUpdate($query, [$idUser, $dia]);
    }
}
