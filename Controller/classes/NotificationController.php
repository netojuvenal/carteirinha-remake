<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../../Model/classes/NotificationModel.php';

class NotificationController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new NotificationModel();
    }

    public function hasNotification($userId)
    {
        return $this->model->hasNotification((int)$userId);
    }

    public function hasNewNotification($userId)
    {
        return $this->model->hasNewNotification((int)$userId);
    }

    public function getNotification($userId, $idNotification = null)
    {
        $notificacoes = $this->model->getNotification((int)$userId, $idNotification);

        // Ajuste das transferências conforme regra antiga (12:00 / data)
        date_default_timezone_set('America/Sao_Paulo');
        $horaAtual = date("H:i:s");
        $diaAtual = date("Y-m-d");

        $lista = [];
        foreach ($notificacoes as $n) {
            if (($n['transferencia'] == 1 && $horaAtual > "12:00:00") || ($n['transferencia'] == 1 && $n['data'] != $diaAtual)) {
                $n['transferencia'] = 0;
            }
            $lista[] = $n;
        }

        return $lista;
    }

    private function getIdRemetente($idDestinatario)
    {
        return $this->model->getTransferenciaData((int)$idDestinatario);
    }

    public function aceitarRefeicao($idDestinatario)
    {
        $idRemetente = $this->getIdRemetente($idDestinatario);
        if (empty($idRemetente)) {
            return ['status' => false, 'message' => 'Falha ao aceitar refeição'];
        }

        if (!$this->model->isActive($idDestinatario) && $this->model->isActive($idRemetente)) {
            if ($this->model->aceitarRefeicao($idDestinatario, $idRemetente)) {
                return ['status' => true, 'message' => 'Refeição aceita com sucesso'];
            } else {
                return ['status' => false, 'message' => 'Falha ao aceitar refeição'];
            }
        } else {
            return ['status' => false, 'message' => 'Já existe reserva ativa no id do destinatário ou do remetente'];
        }
    }

    public function cancelarTransferencia($idDestinatario)
    {
        $idRemetente = $this->getIdRemetente($idDestinatario);
        if (empty($idRemetente)) { return false; }
        return $this->model->changeNotificacaoType($idRemetente);
    }

    public function readNotification($idDestinatario, $idNotification)
    {
        if ($this->model->readNotification((int)$idDestinatario, (int)$idNotification)) {
            return ['status'=> true, 'message'=> 'Notificação lida'];
        } else {
            return ['status'=> false, 'message'=> 'Erro ao ler notificação'];
        }
    }

    public function createNotificacao($idRemetente, $idAlvo, $assunto, $mensagem, $tipo)
    {
        return $this->model->createNotificacao($idRemetente, $idAlvo, $assunto, $mensagem, $tipo);
    }
}
