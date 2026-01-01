<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/AdmModel.php';

class AdmController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new AdmModel();
    }

    public function editarHorario($hora)
    {
        if (!$this->usuarioAdm()) {
            return ['status' => false, 'message' => 'Acesso negado'];
        }

        $ok = $this->model->editarHorario($hora);

        if ($ok) {
            return ['status' => true, 'message' => 'Horário editado com sucesso'];
        } else {
            return ['status' => false, 'message' => 'Erro ao editar horário'];
        }
    }
}
