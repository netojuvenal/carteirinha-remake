<?php
require_once(__DIR__ . "/Model.php");

/**
 * PerfilModel - ações relacionadas ao perfil do usuário (ex: trocar senha)
 */

class PerfilModel extends Model
{
    /**
     * Atualiza senha do usuário usando password_hash
     *
     * @param string $newPassword
     * @param int $idUser
     * @return bool
     */
    public function setPassword(string $newPassword, int $idUser): bool
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET senha = ? WHERE id = ?";
        return $this->executeUpdate($query, [$hash, $idUser], "si");
    }
}
