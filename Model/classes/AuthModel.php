<?php
// Model/classes/AuthModel.php
require_once(__DIR__ . "/Model.php");

class AuthModel extends Model
{
    // Retorna dados do usuário por matrícula
    public function getDataByMatricula($matricula)
    {
        $query = "SELECT id, nome, email, matricula, categoria, telefone FROM usuario WHERE matricula = ?";
        $result = $this->executeQuery($query, [$matricula]);
        return ($result && count($result) > 0) ? $result[0] : false;
    }

    // Valida login
    // Retorna true se senha bate
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

        // Quando migrar pra password_hash(), verificar aqui com password_verify()
        return false;
    }
}
