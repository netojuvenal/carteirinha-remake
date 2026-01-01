<?php

require_once(__DIR__ . "/Model.php");

class AuthModel extends Model
{

    public function getDataByMatricula($matricula)
    {
        $query = "SELECT id, nome, email, matricula, categoria, telefone FROM usuario WHERE matricula = ?";
        $result = $this->executeQuery($query, [$matricula]);
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    public function login($matricula, $pass)
    {
        $query = "SELECT senha, id FROM usuario WHERE matricula = ?";
        $result = $this->executeQuery($query, [$matricula]);
        if (!$result || count($result) === 0) {
            return false;
        }

        $stored = $result[0]['senha'];
        if (md5($pass) === $stored) {
            return true;
        }

        return false;
    }
}
