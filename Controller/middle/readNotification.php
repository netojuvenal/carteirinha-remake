<?php

require_once __DIR__ . '/../classes/NotificationController.php';

$idDest = (int)($_POST['idDestinatario'] ?? 0);
$idNot = (int)($_POST['idNotificacao'] ?? 0);

$ctrl = new NotificationController();
$response = $ctrl->readNotification($idDest, $idNot);

if ($response['status']) {
    echo json_encode(['status' => 'success', 'message' => $response['message']]); exit();
} else {
    echo json_encode(['status' => 'error', 'message' => $response['message']]); exit();
}
