<?php

require_once(__DIR__ . "/Model.php");

class AdmModel extends Model
{
    public function editarHorario($hora)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $dataHora = date("Y-m-d H:i:s");

        $queryMax = "SELECT MAX(id) AS max_id FROM horario_padrao";
        $result = $this->executeQuery($queryMax);
        if ($result === false) {
            return false;
        }

        $maxId = $result[0]['max_id'] ?? null;

        if ($maxId) {
            $update = "UPDATE horario_padrao SET fim_vig = ? WHERE id = ?";
            if (!$this->executeUpdate($update, [$dataHora, $maxId])) {
                return false;
            }
        }

        $insert = "INSERT INTO horario_padrao (horario, inicio_vig) VALUES (?, ?)";
        return $this->executeUpdate($insert, [$hora, $dataHora]);
    }
}
