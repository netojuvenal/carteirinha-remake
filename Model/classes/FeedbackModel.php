<?php

require_once(__DIR__ . "/Model.php");

class FeedbackModel extends Model
{
    public function adicionarFeedback($nota, $idUser, $idCardapio)
    {
        // Nota: manter a assinatura existente do seu código
        $query = "INSERT INTO feedback (id_usuario, id_cardapio, id_nota) VALUES (?, ?, ?)";
        return $this->executeUpdate($query, [$idUser, $idCardapio, $nota]);
    }

    public function getAllFeedback()
    {
        $query = "SELECT * FROM feedback";
        return $this->executeQuery($query);
    }

    public function getUserFeedback($idUser)
    {
        $query = "SELECT * FROM feedback WHERE id_usuario = ?";
        return $this->executeQuery($query, [$idUser]);
    }

    public function getDiaByID($idCardapio)
    {
        $query = "SELECT dia FROM cardapio WHERE id = ? AND ind_excluido = 0";
        return $this->executeQuery($query, [$idCardapio]);
    }

    public function getFeedbackDetails($idCardapio)
    {
        $query = "SELECT 
            f.id AS id,
            u.nome AS nome,
            u.matricula AS matricula,
            f.id_nota,
            f.comentario,
            c.*
            FROM 
            feedback f
            JOIN 
            cardapio c ON f.id_cardapio = c.id
            JOIN 
            usuario u ON f.id_usuario = u.id
            WHERE 
            f.id_cardapio = ?;
        ";

        $result = $this->executeQuery($query, [$idCardapio]);
        return $result;
    }
}
