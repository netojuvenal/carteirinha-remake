<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/AgendamentoModel.php';

class AgendamentoController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new AgendamentoModel();
    }

    // Mantive nomes e assinaturas para compatibilidade com middlewares
    public function hasAgendamento($dia, $idUser)
    {
        return $this->model->hasAgendamento($dia, $idUser);
    }

    public function retirarAlmoco($dia, $idUser)
    {
        return $this->model->retirarAlmoco($dia, $idUser);
    }
}
