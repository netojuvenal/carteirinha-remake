<?php
require_once(__DIR__ . "/Model.php");

class PerfilModel extends Model
{
    public function setPassword(string $newPassword, int $idUser): bool
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET senha = ? WHERE id = ?";
        return $this->executeUpdate($query, [$hash, $idUser], "si");
    }
}
