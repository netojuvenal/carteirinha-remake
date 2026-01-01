<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/FeedbackModel.php';

class FeedbackController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new FeedbackModel();
    }

    public function sendFeedback($nota, $idUser, $idCardapio)
    {
        return $this->model->adicionarFeedback($nota, $idUser, $idCardapio);
    }

    public function getFeedback()
    {
        return $this->model->getAllFeedback();
    }

    public function getUserFeedback($idUser)
    {
        return $this->model->getUserFeedback($idUser);
    }

    public function getDiaByID($idCardapio)
    {
        $resultado = $this->model->getDiaByID($idCardapio);
        return $resultado == null ? null : $resultado;
    }

    public function getFeedbackDetails($idCardapio)
    {
        return $this->model->getFeedbackDetails($idCardapio);
    }
}
