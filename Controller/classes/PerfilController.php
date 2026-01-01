<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/PerfilModel.php';

class PerfilController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new PerfilModel();
    }

    public function setPassword($newPassword, $idUser)
    {
        // validação simples (não faz hashing agora — você pediu pra manter md5 por enquanto)
        if (empty($newPassword) || empty($idUser)) {
            return false;
        }
        return $this->model->setPassword($newPassword, $idUser);
    }
}
