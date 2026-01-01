<?php

require_once __DIR__ . '/../classes/CardapioController.php';
require_once __DIR__ . '/../classes/NotificationController.php';

$matriculaAlvo = $_POST['matriculaAlvo'] ?? '';
$idUser = (int)($_POST['idUser'] ?? 0);
$motivo = $_POST['motivo'] ?? '';

$cardCtrl = new CardapioController();
$idAlvo = $cardCtrl->getIdByMatricula($matriculaAlvo);

if ($idAlvo === false || $idAlvo === null) {
    echo json_encode(['status'=> 'error', 'message' => 'Erro ao pegar id por matricula']); exit();
}

$notifCtrl = new NotificationController();
$response = $notifCtrl->createNotificacao($idUser, (int)$idAlvo, 'Transferencia de almoço', "Motivo da transferência: " . $motivo, 1);

if ($response['status']) {
    echo json_encode(['status'=> 'success', 'message' => 'sucesso ao criar notificação']); exit();
} else {
    echo json_encode(['status'=> 'error', 'message' => $response['message']]); exit();
}
